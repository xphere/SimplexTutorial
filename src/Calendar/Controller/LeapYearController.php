<?php

namespace Calendar\Controller;

use Symfony\Component\HttpFoundation\Response;
use Calendar\Model\Year;

class LeapYearController
{
    public function indexAction($year = null)
    {
        $year = new Year($year ?: date('Y'));
        if ($year->isLeapYear()) {
            $response = new Response('Yep, this is a leap year!');
        } else {
            $response = new Response('Nope, this is not a leap year.');
        }

        $response->setTtl(100)
                 ->setContent('<p>' . $response->getContent() . '</p>');

        $response->headers->set('X-Random-Value', mt_rand(1, 100));

        return $response;
    }
}
