<?php

namespace Tools\Date;

/**
 * @copyright (c) 2012-2013, Jitendra Adhikari
 * @author Jitendra Adhikari <jiten.adhikary@gmail.com>
 */
class ToString
{
    private $date   = null;
    private $string = null;

    public function __construct($date = null)
    {
        (is_null($date)) or $this->date = $date;
    }

    /**
     * @param $date the datetime in formats supported by date()
     *
     * @return \Tools\Date\ToString | chaining
     */
    public function setDate($date)
    {
        $this->date   = $date;
        $this->string = null;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string if the $date is defined null otherwise
     *
     * @see https://gist.github.com/anonymous/7214295 for other version
     */
    public function getString()
    {
        if (is_null($this->date)) {
            return null;
        }
        if (is_null($this->string)) {
            $names = [
                'decade' => 315360000,
                'year'   => 31536000,
                'month'  => 2592000,
                'week'   => 604800,
                'day'    => 86400,
                'hour'   => 3600,
                'minute' => 60,
                'second' => 1,
            ];

            $diff   = abs(($now = time()) - ($time = strtotime($this->date)));
            $suffix = ($diff < 2) ? 'just now' : (($time < $now) ? 'ago' : 'from now');
            $string = '';

            foreach ($names as $name => $factor) {
                if (($quot = floor($diff / $factor)) > 0) {
                    $string .= $quot . ' ' . $name . ($quot == 1 ? '' : 's') . ' ';
                    $diff -= $quot * $factor;
                }
            }

            $this->string = $string . $suffix;
        }

        return $this->string;
    }

    public function __toString()
    {
        return $this->getString();
    }
}
