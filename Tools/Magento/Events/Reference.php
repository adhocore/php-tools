<?php

/**
 * Generates table with info of magento events
 * 
 * A handy tool that generates table of all the core magento events
 * with the class name that fires the event. The table also shows the
 * line number at which the event is dispatched and the arguments sent
 * to the event Listener. Events can be used to hook the system without
 * hacking core. This is the ulimate reference for magento Events.
 * To significantly improve performance for subsequent usage, it writes 
 * cache to the path specified by $cachePath
 * Cheers!
 * 
 * @package Tools
 * @subpackage Magento
 * @author adhocore | Jitendra Adhikari <jiten.adhikary@gmail.com>
 * @copyright (c) 2013, Jitendra Adhikari
 * @link https://github.com/adhocore/php-tools the main repository of this tool
 * 
 */
namespace Tools\Magento\Events;

class Reference {

    private $corePath;
    private $cachePath;
    private $data = array();

    private function getIterator() {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->corePath)
        );
    }

    private function scanData() {
        if (empty($this->data)) {
            $data = array();            
            foreach ($this->getIterator() as $file) {
                if (! is_file($file = str_replace('\\', '/', $file)) or 
                    ($parts = explode('.', strtolower($file)) and 
                        end($parts) !== 'php'))
                    continue;                
                ($grep = preg_grep('~Mage::dispatchEvent\((.*)\);~', array_merge(array(''), file($file))))
                    and $data[$this->classify($file)] = $this->parseData($grep);
            }
            $this->data = $data;
            unset($data);
        }
        return $this->data;
    }

    private function parseData($events) {
        foreach ($events as $line => &$event) {
            $parts = explode(',', trim($event), 2);
            $event = array(
                substr(trim($parts[0], ' /*);'), 20),
                substr(isset($parts[1])?$parts[1]:'n/a  ', 0, -2)
            );             
        }
        return $events;
    }

    private function classify($path) {
        return (strpos($path, 'controllers') === false) 
            ? str_replace(array($this->corePath.'/', '.php', '/'), array('', '', '_'), $path)
            : str_replace($this->corePath.'/', '', $path);
    }

    private function writeCache() {        
        return @file_put_contents($this->cachePath . '/mage-evt.tbl', 
            serialize($this->scanData()), LOCK_EX
        );
    }

    public function setCorePath($corePath) {
        $this->corePath = rtrim($corePath, '/');
        return $this;
    }
    
    public function setCachePath($cachePath) {
        $this->cachePath = $cachePath;
        return $this;
    }

    public function tabulate($return = false) {
        if ($x = is_null($this->corePath) or is_null($this->cachePath)) {
            throw new \Exception("Invalid path for ".($x?'$corePath':'$cachePath'));
        }
        if (! is_file($this->cachePath . '/mage-evt.tbl')) {
            if (! $this->writeCache()) {
                throw new \Exception("Cannot write cache.");                
            }
        }
        $table = 
        '<table><thead><tr>
            <th>Event Dispatcher Class <i>(path in case of controller)</i></th><th>Line No.</th><th>Event Name</th><th>Event Args</th>
        </tr></thead><tbody>';
        foreach (unserialize(file_get_contents($this->cachePath . '/mage-evt.tbl')) as $class => $events) {
            foreach ($events as $line => $event) {
                $table .= "<tr><td>{$class}</td><td>{$line}</td><td>{$event[0]}</td><td>{$event[1]}</td></tr>";
            }
        }
        $table .= '</tbody></table>';
        if ($return === true) return $table;
        echo $table;
    }
    
}

# usage: 
/*
$reference = new Tools\Magento\Events\Reference();
$generator
    ->setCorePath('./app/code/core') // path where Mage namespace lives
    ->setCachePath('./var')
    ->tabulate()
;
*/