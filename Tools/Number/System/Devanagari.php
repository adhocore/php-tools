<?php

namespace Tools\Number\System;

/**
 * @copyright (c) 2012-2013, Jitendra Adhikari
 * 
 * @package Tools
 * @subpackage Number
 * @author Jitendra Adhikari <jiten.adhikary@gmail.com>
 * 
 */
class Devanagari extends System {

    public function __construct($number = null) {
        parent::__construct($number);
        $this->groups = array("", " thousand", " lakh", " crore", " arab", " kharab", " neel", " padma",
            " shankha", " mahashankha", " padha", " madh", " paraardha", " ant", " mahaant", " shisht", " shinghar");
    }

    public function setDelta() {
        $this->delta = 2;
    }

}