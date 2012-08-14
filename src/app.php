<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('leap_year', new Route('/is_leap_year/{year}', array(
    'year' => null,
    '_controller' => 'LeapYearController::indexAction',
)));

function is_leap_year($year)
{
    return $year % 400 === 0 || ($year % 4 === 0 && $year % 100 !== 0);
}

class LeapYearController
{
    public function indexAction($year)
    {
        if (is_leap_year($year ?: date('Y'))) {
            return new Response('Yep, this is a leap year!');
        }
        return new Response('Nope, this is not a leap year.');
    }
}

return $routes;
