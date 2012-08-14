<?php

namespace Calendar\Model;

class Year
{
    private $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function isLeapYear()
    {
        return $this->year % 400 === 0 || ($this->year % 4 === 0 && $this->year % 100 !== 0);
    }
}
