<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
abstract class BaseTestCase extends TestCase
{
    /** @var  App */
    protected $app;
    /**
     * Use middleware when running application?
     *
     * @var bool
     */
    protected $withMiddleware = true;

    /**
     * If we want to setup app in other way
     *
     * @var array
     */
    protected $customSettings = [];

    /**
     * @param $requestMethod
     * @param $requestUri
     * @param null $requestData
     * @param array $headers
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function runApp($requestMethod, $requestUri, $requestData = null, $headers = [])
    {
        $headers = array_merge([
            'REQUEST_METHOD' => $requestMethod,
            'REQUEST_URI' => $requestUri,
            'CONTENT_TYPE' => 'text/html',
        ], $headers);
        // Create a mock environment for testing with
        $environment = Environment::mock($headers);
        // Set up a request object based on the environment
        $request = Request::createFromEnvironment($environment);
        // Add request data, if it exists
        if (isset($requestData)) {
            $request = $request->withParsedBody($requestData);
        }
        // Set up a response object
        $response = new Response();

        // Process the application and Return the response
        return $this->app->process($request, $response);
    }

    /**
     * @param $requestMethod
     * @param $requestUri
     * @param null $requestData
     * @param array $headers
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function request($requestMethod, $requestUri, $requestData = null, $headers = [])
    {
        return $this->runApp($requestMethod, $requestUri, $requestData, $headers);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->createApplication();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($this->app);
        parent::tearDown();
    }

    protected function createApplication()
    {
        $settings = array_merge(require __DIR__ . '/../config/web.php', $this->customSettings);
        $this->app = $app = new App($settings);

        require __DIR__ . '/../src/Base/dependencies.php';

        if ($this->withMiddleware) {
            require __DIR__ . '/../src/Base/middlewares.php';
        }

        require __DIR__ . '/../src/Base/routes.php';
    }
}
