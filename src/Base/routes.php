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

    $app->group('/songs', function () {
        /** @var $app App */
        $app = $this;
        $app->get('', function (Request $request, Response $response) {
            $this->logger->info("'/admin/songs' route");

            return $this->view->render($response, 'admin.twig', ['songs' => $this->songs->find()]);
        });

        $app->get('/add', function (Request $request, Response $response) {
            $this->logger->info("'/admin/songs/add' route");

            return $this->view->render($response, 'songs/_form.twig');
        })->setName('song.add');

        $app->get('/edit/{id}', function (Request $request, Response $response) {
            $id = $request->getAttribute('id', '');
            $this->logger->info("'/admin/songs/edit/{$id}' route");
            $song = $this->songs->read($id);

            return $this->view->render($response, 'songs/_form.twig', ['song' => $song]);
        })->setName('song.edit');
    });
})->add(new AdminAuthMiddleware($app->getContainer()));
