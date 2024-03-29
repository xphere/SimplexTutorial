<?php

namespace Simplex\Tests;

use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class FrameworkTest extends \PHPUnit_Framework_TestCase
{
    const URL_MATCHER = 'Symfony\Component\Routing\Matcher\UrlMatcherInterface';
    const CONTROLLER_RESOLVER = 'Symfony\Component\HttpKernel\Controller\ControllerResolverInterface';
    const EVENT_DISPATCHER = 'Symfony\Component\EventDispatcher\EventDispatcherInterface';

    public function testNotFoundHandling()
    {
        $framework = $this->getFrameworkForException(new ResourceNotFoundException());
        $response = $framework->handle(new Request());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testErrorHandling()
    {
        $framework = $this->getFrameworkForException(new \RuntimeException());
        $response = $framework->handle(new Request());
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testControllerResponse()
    {
        $dispatcher = $this->getMock(self::EVENT_DISPATCHER);
        $matcher = $this->getMock(self::URL_MATCHER);
        $matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->returnValue(array(
                '_route' => 'foo',
                'name' => 'Fabien',
                '_controller' => function ($name) {
                    return new Response('Hello ' . $name);
                }
            )))
        ;
        $resolver = new ControllerResolver();
        $framework = new Framework($dispatcher, $matcher, $resolver);
        $response = $framework->handle(new Request());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Hello Fabien', $response->getContent());
    }

    protected function getFrameworkForException($exception)
    {
        $dispatcher = $this->getMock(self::EVENT_DISPATCHER);
        $matcher = $this->getMock(self::URL_MATCHER);
        $matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->throwException($exception))
        ;
        $resolver = $this->getMock(self::CONTROLLER_RESOLVER);
        return new Framework($dispatcher, $matcher, $resolver);
    }
}
