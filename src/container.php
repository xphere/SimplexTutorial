<?php

use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Reference;

$dic = new DependencyInjection\ContainerBuilder();

$dic->setParameter('charset', 'UTF-8');

$dic->register('context', 'Symfony\Component\Routing\RequestContext');

$dic->register('matcher', 'Symfony\Component\Routing\Matcher\UrlMatcher')
    ->setArguments(array('%routes%', new Reference('context')));

$dic->register('resolver', 'Symfony\Component\HttpKernel\Controller\ControllerResolver');

$dic->register('listener.router', 'Symfony\Component\HttpKernel\EventListener\RouterListener')
    ->setArguments(array(new Reference('matcher')));

$dic->register('listener.response', 'Symfony\Component\HttpKernel\EventListener\ResponseListener')
    ->setArguments(array('%charset%'));

$dic->register('listener.exception', 'Symfony\Component\HttpKernel\EventListener\ExceptionListener')
    ->setArguments(array('Simplex\Controller\ErrorController::exceptionAction'));

$dic->register('listener.content_length', 'Simplex\Event\ContentLengthListener');
$dic->register('listener.string_response', 'Simplex\Event\StringResponseListener');

$dic->register('dispatcher', 'Symfony\Component\EventDispatcher\EventDispatcher')
    ->addMethodCall('addSubscriber', array(new Reference('listener.router')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.response')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.exception')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.content_length')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.string_response')))
;

$dic->register('framework', 'Simplex\Framework')
    ->setArguments(array(new Reference('dispatcher'), new Reference('resolver')));

return $dic;
