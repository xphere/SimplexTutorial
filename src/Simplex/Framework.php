<?php

namespace Simplex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Simplex\Event\ResponseEvent;

class Framework implements HttpKernelInterface
{
    private $dispatcher;
    private $matcher;
    private $resolver;

    public function __construct(EventDispatcherInterface $dispatcher, UrlMatcherInterface $matcher, ControllerResolverInterface $resolver)
    {
        $this->dispatcher = $dispatcher;
        $this->matcher = $matcher;
        $this->resolver = $resolver;
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        try {
            $request->attributes->add($this->matcher->match($request->getPathInfo()));

            $controller = $this->resolver->getController($request);
            $arguments = $this->resolver->getArguments($request, $controller);

            $response = call_user_func_array($controller, $arguments);

        } catch (ResourceNotFoundException $e) {
            $response = new Response('Not Found', 404);

        } catch (\Exception $e) {
            $response = new Response('An error occurred', 500);
        }

        $event = new ResponseEvent($response, $request);
        $this->dispatcher->dispatch(Events::RESPONSE, $event);
        return $event->getResponse();
    }
}
