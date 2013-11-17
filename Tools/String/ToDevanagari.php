<?php

namespace Tools\String;

/**
 * @copyright (c) 2012-2013, Jitendra Adhikari
 * 
 * @package Tools
 * @subpackage String
 * @author Jitendra Adhikari <jiten.adhikary@gmail.com>
 * 
 */
class ToDevanagari {

    private $input;
    private $output;
    private $parser;

    public function __construct($input = NULL, $html = FALSE) {
        $this->setInput($input);
        $this->parser = new Parse\Parser($this->input, $html);
    }

    public function setInput($input) {
        $this->input = $input;
        $this->output = null;
        return $this;
    }

    public function setHtmlEntity() {
        $previous = $this->parser->setHtmlEntity();
        if (!$previous) {
            $this->output = null;
        }
        return $this;
    }

    public function getInput() {
        return $this->input;
    }

    public function debug() {
        $this->parser->getLexer()->tokensView();
    }

    public function unicode() {
        if (is_null($this->output)) {
            $this->output = $this->parser->getParsed();
        }
        return $this->output;
    }

    public function __toString() {
        return $this->unicode();
    }

}