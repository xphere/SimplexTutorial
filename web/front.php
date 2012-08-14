<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$routes = require __DIR__ . '/../src/app.php';
$dic = require __DIR__ . '/../src/container.php';

$dic->get('framework')
    ->handle($request)
    ->send();
