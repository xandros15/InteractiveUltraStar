<?php

use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

$app = new App(require __DIR__ . '/../config/web.php');

require __DIR__ . '/../src/Base/dependencies.php';
require __DIR__ . '/../src/Base/routes.php';
require __DIR__ . '/../src/Base/middlewares.php';

$app->run();
