<?php


namespace UltraStar\Middlewares;


use Slim\Http\Request;
use Slim\Http\Response;


class AdminAuthMiddleware extends AbstractMiddleware
{
    public function __invoke(Request $request, Response $response, $next): Response
    {
        $auth = $this->settings['auth'];
        $login = $request->getServerParam('PHP_AUTH_USER');
        $password = $request->getServerParam('PHP_AUTH_PW');
        if ($login != $auth['login'] || $password != $auth['password']) {
            return $response->withHeader('WWW-Authenticate', 'Basic realm="My Realm"')
                            ->withStatus(401);
        }

        return $next($request, $response);
    }
}
