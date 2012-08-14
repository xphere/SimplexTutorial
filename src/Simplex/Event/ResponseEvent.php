<?php

namespace Simplex\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseEvent extends Event
{
    /**
     * @var Response
     */
    private $response;

    /**
     * @var Request
     */
    private $request;

    function __construct($response, Request $request)
    {
        $this->response = $response instanceof Response ? $response : new Response($response);
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}
