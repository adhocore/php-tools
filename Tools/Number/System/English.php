<?php

namespace Tools\Number\System;

/**
 * @copyright (c) 2012-2013, Jitendra Adhikari
 * @author Jitendra Adhikari <jiten.adhikary@gmail.com>
 */
class English extends System
{
    public function __construct($number = null)
    {
        parent::__construct($number);
        $this->groups = ['', ' thousand', ' million', ' billion', ' trillion', ' quadrillion',
            ' quintrillion', ' sextillion', ' septillion', ' octillion', ' nonillion', ' decillion', ];
    }

    public function setDelta()
    {
        $this->delta = 3;
    }
}
