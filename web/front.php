<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Simplex\Event\ContentLengthListener;
use Calendar\Event\LegalListener;

$request = Request::createFromGlobals();
$routes = require __DIR__ . '/../src/app.php';

$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);
$resolver = new HttpKernel\Controller\ControllerResolver();

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new LegalListener());
$dispatcher->addSubscriber(new ContentLengthListener());

$framework = new Simplex\Framework($dispatcher, $matcher, $resolver);
$response = $framework->handle($request);

$response->send();
