<?php

namespace Tools\String\Parse;

/**
 * @copyright (c) 2012-2013, Jitendra Adhikari
 * 
 * @package Tools
 * @subpackage String
 * @author Jitendra Adhikari <jiten.adhikary@gmail.com>
 * 
 */
class Parser {

    private $lexer;
    private $charMap;
    private $numMap;
    private $modMap;
    private $utfMap;
    private $html = false;

    public function __construct($input = null, $html = false) {
        $this->html = (bool) $html;
        $this->lexer = new Lexer($this->normalizeInput($input));

        $this->charMap = unserialize('a:67:{s:1:".";i:2404;s:2:"..";i:2405;s:1:"A";i:2310;s:1:"D";i:2337;s:2:"Dh";i:2338;s:1:"E";i:2312;s:1:"H";i:2307;s:1:"I";i:2312;s:3:"Lri";i:2316;s:1:"M";i:2306;s:2:"MM";i:2305;s:1:"N";i:2339;s:1:"O";i:2321;s:1:"R";i:2371;s:2:"Ri";i:2371;s:1:"S";i:2358;s:1:"T";i:2335;s:2:"Th";i:2336;s:1:"U";i:2314;s:1:"a";i:2309;s:2:"aa";i:2310;s:2:"ai";i:2320;s:2:"au";i:2324;s:1:"b";i:2348;s:2:"bh";i:2349;s:1:"c";s:4:"2325";s:2:"cH";i:2331;s:2:"ch";i:2330;s:3:"chh";i:2331;s:1:"d";i:2342;s:2:"dh";i:2343;s:1:"e";i:2319;s:2:"ee";i:2311;s:1:"f";i:2347;s:1:"g";i:2327;s:2:"gh";i:2328;s:1:"h";i:2361;s:1:"i";i:2311;s:2:"ii";i:2312;s:1:"j";i:2332;s:2:"jh";i:2333;s:1:"k";i:2325;s:2:"kh";i:2326;s:1:"l";i:2354;s:1:"m";i:2350;s:1:"n";i:2344;s:2:"ng";i:2329;s:1:"o";i:2323;s:2:"oo";i:2314;s:2:"ou";i:2324;s:1:"p";i:2346;s:2:"ph";i:2347;s:1:"q";i:2325;s:1:"r";i:2352;s:3:"rhi";i:2315;s:1:"s";i:2360;s:2:"sh";i:2359;s:1:"t";i:2340;s:2:"th";i:2341;s:1:"u";i:2313;s:2:"uu";i:2314;s:1:"v";i:2349;s:1:"w";i:2357;s:1:"x";s:4:"2331";s:1:"y";i:2351;s:2:"yn";i:2334;s:1:"z";i:2332;}');
        $this->numMap = unserialize('a:10:{i:0;s:4:"2406";i:1;s:4:"2407";i:2;s:4:"2408";i:3;s:4:"2409";i:4;s:4:"2410";i:5;s:4:"2411";i:6;s:4:"2412";i:7;s:4:"2413";i:8;s:4:"2414";i:9;s:4:"2415";}');
        $this->modMap = unserialize('a:21:{s:1:"A";i:2366;s:2:"aa";i:2366;s:1:"i";i:2367;s:1:"I";i:2368;s:2:"ii";i:2368;s:2:"ee";i:2368;s:1:"u";i:2369;s:1:"U";i:2370;s:2:"uu";i:2370;s:2:"oo";i:2370;s:1:"R";i:2371;s:1:"e";i:2375;s:2:"ai";i:2376;s:2:"ei";i:2376;s:1:"o";i:2379;s:2:"ou";i:2380;s:2:"au";i:2380;s:1:".";i:2381;s:2:"Om";i:2384;s:2:"OM";i:2384;s:2:"Ri";i:2371;}');
        $this->utfMap = unserialize('a:111:{i:2305;s:3:"ँ";i:2306;s:3:"ं";i:2307;s:3:"ः";i:2308;s:3:"ऄ";i:2309;s:3:"अ";i:2310;s:3:"आ";i:2311;s:3:"इ";i:2312;s:3:"ई";i:2313;s:3:"उ";i:2314;s:3:"ऊ";i:2315;s:3:"ऋ";i:2316;s:3:"ऌ";i:2317;s:3:"ऍ";i:2318;s:3:"ऎ";i:2319;s:3:"ए";i:2320;s:3:"ऐ";i:2321;s:3:"ऑ";i:2322;s:3:"ऒ";i:2323;s:3:"ओ";i:2324;s:3:"औ";i:2325;s:3:"क";i:2326;s:3:"ख";i:2327;s:3:"ग";i:2328;s:3:"घ";i:2329;s:3:"ङ";i:2330;s:3:"च";i:2331;s:3:"छ";i:2332;s:3:"ज";i:2333;s:3:"झ";i:2334;s:3:"ञ";i:2335;s:3:"ट";i:2336;s:3:"ठ";i:2337;s:3:"ड";i:2338;s:3:"ढ";i:2339;s:3:"ण";i:2340;s:3:"त";i:2341;s:3:"थ";i:2342;s:3:"द";i:2343;s:3:"ध";i:2344;s:3:"न";i:2345;s:3:"ऩ";i:2346;s:3:"प";i:2347;s:3:"फ";i:2348;s:3:"ब";i:2349;s:3:"भ";i:2350;s:3:"म";i:2351;s:3:"य";i:2352;s:3:"र";i:2353;s:3:"ऱ";i:2354;s:3:"ल";i:2355;s:3:"ळ";i:2356;s:3:"ऴ";i:2357;s:3:"व";i:2358;s:3:"श";i:2359;s:3:"ष";i:2360;s:3:"स";i:2361;s:3:"ह";i:2362;s:3:"ऺ";i:2363;s:3:"ऻ";i:2364;s:3:"़";i:2365;s:3:"ऽ";i:2366;s:3:"ा";i:2367;s:3:"ि";i:2368;s:3:"ी";i:2369;s:3:"ु";i:2370;s:3:"ू";i:2371;s:3:"ृ";i:2372;s:3:"ॄ";i:2373;s:3:"ॅ";i:2374;s:3:"ॆ";i:2375;s:3:"े";i:2376;s:3:"ै";i:2377;s:3:"ॉ";i:2378;s:3:"ॊ";i:2379;s:3:"ो";i:2380;s:3:"ौ";i:2381;s:3:"्";i:2382;s:3:"ॎ";i:2383;s:3:"ॏ";i:2384;s:3:"ॐ";i:2385;s:3:"॑";i:2386;s:3:"॒";i:2387;s:3:"॓";i:2388;s:3:"॔";i:2389;s:3:"ॕ";i:2390;s:3:"ॖ";i:2391;s:3:"ॗ";i:2392;s:6:"क़";i:2393;s:6:"ख़";i:2394;s:6:"ग़";i:2395;s:6:"ज़";i:2396;s:6:"ड़";i:2397;s:6:"ढ़";i:2398;s:6:"फ़";i:2399;s:6:"य़";i:2400;s:3:"ॠ";i:2401;s:3:"ॡ";i:2402;s:3:"ॢ";i:2403;s:3:"ॣ";i:2404;s:3:"।";i:2405;s:3:"॥";i:2406;s:3:"०";i:2407;s:3:"१";i:2408;s:3:"२";i:2409;s:3:"३";i:2410;s:3:"४";i:2411;s:3:"५";i:2412;s:3:"६";i:2413;s:3:"७";i:2414;s:3:"८";i:2415;s:3:"९";}');	
    }

    private function normalizeInput($input) {
        $search = explode(' ', 'B C F J K P Q V W Y Z');
        $replace = explode(' ', 'b c f j k p q v w y z');
        return str_replace($search, $replace, $input);
    }

    public function setHtmlEntity() {
        $previous = $this->html;
        $this->html = true;
        return $previous;
    }

    public function getParsed() {
        $output = '';
        $this->lexer->reset();
        $this->lexer->moveLexer();
        $this->lexer->moveLexer();
        list($prevValue, $prevToken) = array(NULL, NULL);

        while ($this->lexer->token) {
            list( $tokenValue, $tokenType, $lookaheadValue, $lookaheadType ) = $this->fetchFromLexer();
            if ($tokenType === Lexer::T_CONSONANT) {
                if (strlen($tokenValue) == 1) {
                    $value = $lookaheadType === Lexer::T_VOWEL ? $this->getChar($tokenValue . substr($lookaheadValue, 0, 1)) : $this->getChar($tokenValue . $lookaheadValue);
                    if ($value) {
                        $output .= $value;
                        $this->lexer->moveLexer();
                    } else {
                        $output .= $this->getChar($tokenValue);
                        if ($lookaheadType === Lexer::T_CONSONANT && $tokenValue != 'H' && $tokenValue != 'M') {
                            $output .= $this->getModifier('.');
                        }
                    }
                }

                if (strlen($tokenValue) > 1) {
                    if ($value = $this->getChar($tokenValue . $lookaheadValue)) {
                        $output .= $value;
                        $this->lexer->moveLexer();
                    } else {
                        if ($try = $this->getChar($tokenValue)) {
                            $output .= $try;
                        } else {
                            if (in_array($tokenValue, array('Gn', 'Gy'))) {
                                $output .= $this->getChar('j') . $this->getModifier('.') . $this->getChar('yn');
                            } else if ($tokenValue[1] == 'R') {
                                $output .= $this->getChar($tokenValue[0]) . $this->getChar($tokenValue[1]);
                                if ($lookaheadType === Lexer::T_VOWEL)
                                    $this->lexer->moveLexer();
                                else
                                    $try = true;
                            }
                            else {
                                if ($tokenValue[1] == 'M' and $lookaheadValue == 'M') {
                                    $output .= $this->getChar($tokenValue[0]) . $this->getChar($lookaheadValue . $lookaheadValue);
                                    $this->lexer->moveLexer();
                                } else {
                                    if ($tokenValue[0] == 'M') {
                                        $output .= $this->getChar('M') . $this->getChar($tokenValue[1]);
                                    } else {
                                        if ($tokenValue[1] == 'M' or $tokenValue[1] == 'H') {
                                            $output .= $this->getChar($tokenValue[0]) . $this->getChar($tokenValue[1]);
                                        } else {
                                            $output .= $this->getChar($tokenValue[0]) . $this->getModifier('.') . $this->getChar($tokenValue[1]);
                                        }
                                    }
                                }
                            }
                        }

                        if (isset($tokenValue[2]) and !$try) {
                            $output .= $this->getModifier('.') . $this->getChar($tokenValue[2]);
                        }
                        if ($lookaheadType === Lexer::T_CONSONANT && ($lookaheadValue[0] != 'R' && $lookaheadValue != 'H' && $lookaheadValue != 'M' && $tokenValue != 'M' && $lookaheadValue != 'MM')) { // && $tokenValue[1] != 'M' && $tokenValue[1] != 'H') {
                            $output .= $this->getModifier('.');
                        }
                    }
                }
            }
            if ($tokenType === Lexer::T_DOT) {
                $output .= $this->getChar($tokenValue);
            }

            if ($tokenType === Lexer::T_VOWEL) {
                $value = false;
                $tokenValue = substr($tokenValue, 0, 2);
                if (($prevToken and $prevToken != Lexer::T_CONSONANT) or !$prevToken) {
                    $output .= ($value) ? $value : $this->getChar($tokenValue);
                } else {
                    $output .= ($value) ? $value : $this->getModifier($tokenValue);
                }
                if ($value) {
                    $this->lexer->moveLexer();
                }
            }
            if ($tokenType === Lexer::T_NUMBER) {
                $output .= $this->getNumber($tokenValue);
            }
            if ($tokenType === Lexer::T_WHITE || $tokenType === Lexer::T_CHAR) {
                $output .= $tokenValue;
            }
            list( $prevValue, $prevToken ) = array($tokenValue, $tokenType);
            $this->lexer->moveLexer();
        }

        return $output;
    }

    public function getLexer() {
        return $this->lexer;
    }

    private function fetchFromLexer() {
        return array(
            $this->lexer->token['value'],
            $this->lexer->token['type'],
            $this->lexer->lookahead['value'],
            $this->lexer->lookahead['type'],
        );
    }

    private function getChar($c = NULL) {
        if (!isset($this->charMap[$c]))
            return FALSE;
        return ( $this->html ) ? '&#' . $this->charMap[$c] . ';' : $this->getUTF8($this->charMap[$c]);
    }

    private function getModifier($c = NULL) {
        if (!isset($this->modMap[$c]))
            return FALSE;
        return ( $this->html ) ? '&#' . $this->modMap[$c] . ';' : $this->getUTF8($this->modMap[$c]);
    }

    private function getUTF8($c = NULL) {
        return isset($this->utfMap[$c]) ? $this->utfMap[$c] : FALSE;
    }

    private function getNumber($number) {
        $return = '';
        for ($i = 0; $i < strlen($number); $i++) {
            $digit = substr($number, $i, 1);
            if (!isset($this->numMap[$digit]))
                $return .= $digit;
            else {
                $return .= ( $this->html ) ? '&#' . $this->numMap[$digit] . ';' : $this->getUTF8($this->numMap[$digit]);
            }
        }
        return $return;
    }

}