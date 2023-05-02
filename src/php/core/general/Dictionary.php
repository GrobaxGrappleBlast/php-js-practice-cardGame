<?php
require_once 'src/php/core/core.php';
class Dictionary {

    private $internalDictionary; 
    function __construct() {
        $this->internalDictionary = array();
    }
    
    function add($key, $value) :void {
        $this->internalDictionary[$key] = $value;
    }
    
    function remove($key) :void {
        unset($this->internalDictionary[$key]);
    }
    
    function contains($key) : bool{
        return array_key_exists($key, $this->internalDictionary);
    }
    
    public function getKeys() : array {
        $keys = array();
        foreach ($this->internalDictionary as $key => $value) {
            array_push($keys, $key);
        }
        return $keys;
    }
} 
     
?>