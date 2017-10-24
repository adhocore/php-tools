<?php

namespace Tools\Number;

/**
 * @copyright (c) 2012-2013, Jitendra Adhikari
 * @author Jitendra Adhikari <jiten.adhikary@gmail.com>
 */
class ToWords
{
    /**
     * @var System\System
     */
    private $system;
    private $number;

    public function __construct($number = null, $system = 'English')
    {
        (is_null($number)) or $this->number = $number;
        (is_null($system)) or $this->setSystem($system);
    }

    public function setNumber($number)
    {
        $this->number = $number;
        $this->system->setNumber($number);

        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setSystem($system)
    {
        if ($system == 'Devanagari') {
            $this->system = new System\Devanagari($this->number);
        } else {
            $this->system = new System\English($this->number);
        }

        return $this;
    }

    /**
     * @return System\System
     */
    public function getSystem()
    {
        return $this->system;
    }

    public function convert()
    {
        return $this->system->toWords();
    }

    public function __toString()
    {
        return $this->convert();
    }
}
