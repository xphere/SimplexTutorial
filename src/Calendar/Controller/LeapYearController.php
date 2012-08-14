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
            return new Response('Yep, this is a leap year!');
        }
        return new Response('Nope, this is not a leap year.');
    }
}
