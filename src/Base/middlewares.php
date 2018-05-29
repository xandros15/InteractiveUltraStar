<?php

use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\HttpCache\Cache;

/** @var $container Container */
$container = $app->getContainer();

/** @var $app App */
$app->add(function (Request $request, Response $response, $next) {
    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
        // permanently redirect paths with a trailing slash
        // to their non-trailing counterpart
        $uri = $uri->withPath(substr($path, 0, -1));

        if ($request->getMethod() == 'GET') {
            return $response->withRedirect((string) $uri, 301);
        } else {
            return $next($request->withUri($uri), $response);
        }
    }

    return $next($request, $response);
});

if (!$container->settings['displayErrorDetails']) { //turn off cache on dev
    $app->add(new Cache('public', 86400));
}
