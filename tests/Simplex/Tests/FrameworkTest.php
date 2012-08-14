<?php

namespace Simplex\Tests;

use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class FrameworkTest extends \PHPUnit_Framework_TestCase
{
    const URL_MATCHER = 'Symfony\Component\Routing\Matcher\UrlMatcherInterface';
    const CONTROLLER_RESOLVER = 'Symfony\Component\HttpKernel\Controller\ControllerResolverInterface';

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

    protected function getFrameworkForException($exception)
    {
        $matcher = $this->getMock(self::URL_MATCHER);
        $matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->throwException($exception))
        ;
        $resolver = $this->getMock(self::CONTROLLER_RESOLVER);
        return new Framework($matcher, $resolver);
    }
}
