<?php

namespace Tools\String\Parse;

/**
 * This Lexer makes use of Abstract Lexer from open source Doctrine project
 * which is licensed under LGPL.
 *
 * @see <http://www.doctrine-project.org>
 */
class Lexer extends Abstracts\Lexer
{
    const T_CHAR      = 'CHR';
    const T_NUMBER    = 'NUM';
    const T_WHITE     = 'SPC';
    const T_VOWEL     = 'VWL';
    const T_CONSONANT = 'CNS';
    const T_DOT       = 'DOT';

    public $token     = null;
    public $lookahead = null;
    private $tokens   = [];
    private $position = 0;

    public function __construct($input)
    {
        $this->tokenize($input);
    }

    protected function getCatchablePatterns()
    {
        return [
            //  '[-]?[0-9]+[.]?[0-9]+', // number
            '[\s]+', // white spaces
            //  '[.][.]+', // dots
            'a[iu]', '[a]{1,2}', '[e]{1,2}', '[i]{1,2}', '[o]{1,2}', '[u]{1,2}', // vowels
            'c[h]{1,2}', '[y][n]', '[G][yn]', '[bcdfghj-np-tv-z][fghlmpqrvz]', // consonants
            '[^a-z0-9.]', // that need not be converted
        ];
    }

    protected function getNonCatchablePatterns()
    {
        return [];
    }

    protected function getType(&$value)
    {
        $type = self::T_CHAR;
        if (is_numeric($value)) {
            return self::T_NUMBER;
        }
        if (preg_match('/[\s]+/', $value, $match)) {
            return self::T_WHITE;
        }
        if ($value == '.' or $value == '..') {
            return self::T_DOT;
        }
        if (preg_match('/^[aeiou]+$/i', $value, $match)) {
            return self::T_VOWEL;
        }
        if (preg_match('/[bcdfghj-np-tv-z]+/i', $value, $match)) {
            return self::T_CONSONANT;
        }

        return $type;
    }

    /**
     * Doctrine\Common\Lexer::scan().
     */
    private function tokenize($input)
    {
        return $this->scan($input);
    }

    /**
     * Doctrine\Common\Lexer::moveNext().
     */
    public function moveLexer()
    {
        return $this->moveNext();
    }

    public function tokensView($index = null)
    {
        echo '<pre>', print_r(is_null($index) ? $this->tokens : $this->tokens[$index], true), '</pre>';
    }
}
