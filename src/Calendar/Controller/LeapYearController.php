<?php

namespace Calendar\Controller;

use Calendar\Model\Year;

class LeapYearController
{
    public function indexAction($year = null)
    {
        $year = new Year($year ?: date('Y'));
        if ($year->isLeapYear()) {
            return '<p>Yep, this is a leap year!</p>';
        }

        return '<p>Nope, this is not a leap year.</p>';
    }
}
