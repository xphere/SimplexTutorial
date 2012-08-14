<?php

namespace Simplex\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\FlattenException;

class ErrorController
{
    public function exceptionAction(FlattenException $exception)
    {
        $msg = 'Something went wrong!<br/>' .
               'Exception:<pre>' . htmlentities($exception->getMessage()).'</pre>';
        return new Response($msg, $exception->getStatusCode());
    }
}
