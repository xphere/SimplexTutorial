<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('hello', new Route('/hello/{name}', array('name' => 'World')));
$routes->add('bye', new Route('/bye'));

function is_leap_year($year)
{
    return $year % 400 === 0 || ($year % 4 === 0 && $year % 100 !== 0);
}

$routes->add('leap_year', new Route('/is_leap_year/{year}', array(
    'year' => null,
    '_controller' => function ($request) {
        $year = $request->attributes->get('year') ?: date('Y');
        if (is_leap_year($year)) {
            return new Response('Yep, this is a leap year!');
        }
        return new Response('Nope, this is not a leap year.');
    }
)));

return $routes;
