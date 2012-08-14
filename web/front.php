<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Reference;

$dic = require __DIR__ . '/../src/container.php';

$dic->setParameter('routes', require __DIR__.'/../src/app.php');

$dic->register('listener.calendar_legal', 'Calendar\Event\LegalListener');
$dic->getDefinition('dispatcher')
    ->addMethodCall('addSubscriber', array(new Reference('listener.calendar_legal')));

$dic->get('framework')
    ->handle(Request::createFromGlobals())
    ->send();
