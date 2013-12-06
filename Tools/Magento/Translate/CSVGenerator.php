<?php

/**
 * Generates data with ready-to-edit CSV for magento translation
 * 
 * A handy tool that generates CSV data for magento theme translation
 * You can set the $mode to:
 *  a) either write that data to translate.csv at path you specify,
 *  b) or just to dump that data in browser for you to copy and use (default). 
 * In the write mode it appends new data to translate.csv thus you dont
 * lose existing translation. 
 * This does not translate the strings form english to others however.
 * It is generator, not translator by the way; nonetheless, its useful ;)
 * 
 * @package Tools
 * @subpackage Magento
 * @author Jitendra Adhikari <jiten.adhikary@gmail.com>
 * @copyright (c) 2013, Jitendra Adhikari
 * @link https://github.com/adhocore/php-tools the main repository of this tool
 * 
 */
namespace Tools\Magento\Translate;

class CSVGenerator {

    const WRITE_CSVFILE = 'write';
    const DUMP_CSVDATA = 'dump';

    private $csvPath;
    private $templatePath;
    private $mode;
    private $data = array();

    public function __construct($templatePath = null, $csvPath = null, $mode = self::DUMP_CSVDATA) {
        (is_null($templatePath)) or $this->templatePath = $templatePath;
        (is_null($csvPath)) or $this->csvPath = $csvPath;
        $this->mode = $mode;
    }

    public function setTemplatePath($templatePath) {
        $this->templatePath = $templatePath;
        $this->data = array();
        return $this;
    }
    
    public function setCsvPath($csvPath) {
        $this->csvPath = $csvPath;
        return $this;
    }
    
    public function setMode($mode) {
         $this->mode = $mode;
         return $this;
    }

    private function getIterator() {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->templatePath)
        );
    }

    private function getData() {
        if (empty($this->data)) {
            $data = $flat = array();
            
            foreach ($this->getIterator() as $file) {
                if (! is_file($file = str_replace('\\', '/', $file)))
                    continue;
                // I assume the template path has .phtml files only, hence commenting below!
                // if ($parts = explode('.', strtolower($file)) and end($parts) !== 'phtml')
                    // continue;
                preg_match_all('~->__\((?:\'|")(.*?)(?:\'|")(\)|,)~', file_get_contents($file), $matches);
                $matches[1] and $data[$file] = $matches[1];
                unset($matches);
            }
            array_walk_recursive($data, function($d) use (&$flat) {
                 $flat[] = trim($d);
            });
            $this->data = array_unique($flat);
            unset($data, $flat);
        }
        return $this->data;
    }

    public function render() {
        if (empty($this->templatePath) or ! is_dir($this->templatePath)) {
            exit('I believe the $templatePath is invalid!');
        }        
        if ($this->mode == self::WRITE_CSVFILE and (empty($this->csvPath) or ! is_dir($this->csvPath))) {
            exit('I believe the $csvPath is invalid!');
        }
        switch ($this->mode) {
            case self::DUMP_CSVDATA:
                exit('<textarea rows="20" cols="100">"' . implode('",""' . PHP_EOL . '"', $this->getData()) . '",""</textarea>');

            case self::WRITE_CSVFILE:
                $flags = FILE_APPEND | LOCK_EX;
                return @file_put_contents(
                    $this->csvPath . '/translate.csv', 
                    PHP_EOL . '"' . implode('",""' . PHP_EOL . '"', $this->getData()) . '",""', 
                    $flags
                );

            default:
                exit('I believe the $mode is unknown!');
        }
    }
    
}

# usage: 
/*
$generator = new Tools\Magento\Translate\CSVGenerator();
$generator->setMode(Tools\Magento\Translate\CSVGenerator::WRITE_CSVFILE)
    ->setTemplatePath('./app/design/frontend/base/default/template')
    ->setCsvPath('./var')
    ->render()
;
*/