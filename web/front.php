<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

$request = Request::createFromGlobals();
$routes = require __DIR__ . '/../src/app.php';

$context = new RequestContext();
$context->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);

try {
    extract($matcher->match($request->getPathInfo()), EXTR_SKIP);
    ob_start();
    require __DIR__ . '/../src/pages/' . $_route . '.php';
    $response = new Response(ob_get_clean());

} catch (ResourceNotFoundException $e) {
    $response = new Response('Not Found', 404);

} catch (Exception $e) {
    $response = new Response('An error occurred', 500);
}

$response->send();
