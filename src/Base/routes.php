<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use UltraStar\Middlewares\AdminAuthMiddleware;

/** @var $app App */

$app->get('/', function (Request $request, Response $response) {
    // Sample log message
    $this->logger->info("'/' route");

    // Render index view
    return $this->view->render($response, 'index.twig');
})->setName('home');

$app->group('/admin', function () {
    /** @var $app App */
    $app = $this;
    $app->get('', function (Request $request, Response $response) {
        $this->logger->info("'/admin' route");

        return $response;
    });
})->add(new AdminAuthMiddleware($app->getContainer()));
