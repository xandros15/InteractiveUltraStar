<?php

use MongoDB\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Slim\App;
use Slim\Container;
use Slim\HttpCache\CacheProvider;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

/** @var $app App */
$container = $app->getContainer();

$container['view'] = function (Container $container) {
    $view = new Twig(__DIR__ . '/../../templates', [
        'cache' => $container->settings['displayErrorDetails'] ? false : __DIR__ . '/../../tmp',
    ]);
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new TwigExtension($container['router'], $basePath));

    if ($container->settings['displayErrorDetails']) {
        $view->addExtension(new Twig_Extension_Debug());
    }

    return $view;
};

$container['cache'] = function () {
    return new CacheProvider();
};

$container['logger'] = function (Container $container) {
    $settings = $container->settings['logger'];
    $logger = new Logger($settings['name']);
    $logger->pushProcessor(new UidProcessor());
    $logger->pushHandler(new StreamHandler($settings['path'], $settings['level']));

    return $logger;
};

$container['database'] = function (Container $container) {
    $client = new Client();

    return $client->selectDatabase($container->settings['database']['name']);
};
