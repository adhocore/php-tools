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
abstract class System {

    protected $tens;
    protected $tenplus;
    protected $ones;
    protected $groups = array();
    protected $delta;
    
    private $number;
    private $decimal;
    private $words;
    private $decimalWords;

    abstract public function setDelta();

    public function __construct($number = null) {
        $this->setNumber($number);
        $this->tens = array("", " ten", " twenty", " thirty", " forty", " fifty",
            " sixty", " seventy", " eighty", " ninety");
        $this->tenplus = array(" ten", " eleven", " twelve", " thirteen", " fourteen",
            " fiften", " sixteen", " seventeen", " eighteen", " nineteen");
        $this->ones = array("", " one", " two", " three", " four", " five",
            " six", " seven", " eight", " nine");
    }

    public function setNumber($number) {
        $this->number = $number;
        $this->words = null;
    }

    private function hundredths($number) {
        $return = "";
        $number = intval($number);

        if ($number > 99) {
            $return .= $this->ones[substr($number, 0, 1)] . " hundred";
            $number = substr($number, 1, 2);
            if ($number > 0)
                $return .= " and";
        }

        return $return .= $this->tenths($number);
    }

    private function tenths($number) {
        $number = intval($number);
        if ($number < 10) {
            return $this->ones[$number];
        }
        if ($number < 20) {
            return $this->tenplus[$number - 10];
        }

        return $this->tens[substr($number, 0, 1)] . $this->ones[substr($number, 1, 1)];
    }

    public function toWords() {
        if ($this->words !== null) {
            return $this->words;
        }

        $this->setDelta();
        $minus = (substr($this->number, 0, 1) == '-');
        $this->words = ($minus) ? "minus" : "";

        if (count($parts = explode('.', $this->number)) >= 2) {
            $this->number = reset($parts);
            $this->decimal = end($parts);

            $ones = $this->ones;
            $this->decimal_words = implode('', array_map(function($n) use ($ones) {
                                return empty($ones[$n]) ? '' : $ones[$n];
                            }, str_split($this->decimal)));
        }

        $reverse = strrev(preg_replace('/^[-0]*/', '', $this->number));
        $length = strlen($reverse);

        if (($length - (3 - $this->delta)) > $this->delta * count($this->groups)) {
            $this->words = "Too long Number.";
        }

        if ($length < 4) {
            if (intval($reverse) == 0) {
                $this->words .= "zero";
            } else {
                $this->words = ($minus ? 'minus' : '') . $this->hundredths(strrev($reverse));
            }
        } else {
            $fragments = array();
            for ($i = 0, $j = 3; $i < $length; $i += $j, $j = $this->delta) {
                $fragments[] = strrev(substr($reverse, $i, $j));
            }
            foreach (array_reverse($fragments, true) as $k => $v) {
                if (intval($v) > 0) {
                    $this->words .= $this->hundredths($v) . $this->groups[$k];
                }
            }
        }

        return $this->words;
    }

}