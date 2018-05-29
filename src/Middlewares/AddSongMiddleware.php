<?php


namespace UltraStar\Middlewares;


use Respect\Validation\Exceptions\AllOfException;
use Respect\Validation\Validator as v;
use Slim\Flash\Messages;
use Slim\Http\Request;
use Slim\Http\Response;

class AddSongMiddleware
{
    private const ALLOWED = [
        'name',
        'artists',
        'tags',
        'languages',
    ];
    /** @var Messages */
    private $flash;

    public function __construct(Messages $messages)
    {
        $this->flash = $messages;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $next
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $next): Response
    {
        $params = $request->getParams(self::ALLOWED);
        $params = $this->normalize($params);

        try {
            v::stringType()->length(3, 512)->setName('Song name')->assert($params['name'] ?? '');

            v::arrayVal()
             ->each(v::stringType()->length(3, 64)->setName('single artist'))
             ->setName('Song artists')
             ->assert($params['artists'] ?? []);
            v::arrayVal()
             ->each(v::stringType()->length(3, 64)->setName('single tag'))
             ->setName('Song tags')
             ->assert($params['tags'] ?? []);
            v::arrayVal()
             ->each(v::stringType()->length(3, 64)->setName('single language'))
             ->setName('Song languages')
             ->assert($params['languages'] ?? []);
        } catch (AllOfException $exception) {
            $this->flash->addMessage('error', nl2br($exception->getFullMessage()));

            return $response->withRedirect(
                $request->getServerParam('HTTP_REFERER', '/')
            );
        }

        $request = $request->withParsedBody($params);

        return $next($request, $response);
    }

    /**
     * @param iterable $params
     *
     * @return array
     */
    private function normalize(iterable $params): array
    {
        $newParams = [];
        foreach ($params as $name => $param) {
            if (is_iterable($param)) {
                $newParams[$name] = $this->normalize($param);
            } else {
                $param = trim($param);
                if ($param) {
                    $newParams[$name] = $param;
                }
            }
        }

        return $newParams;
    }
}
