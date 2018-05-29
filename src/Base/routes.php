<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

/** @var $app App */

$app->get('/', function (Request $request, Response $response) {
    // Sample log message
    $this->logger->info("'/' route");

    // Render index view
    return $this->view->render($response, 'index.twig');
})->setName('home');
