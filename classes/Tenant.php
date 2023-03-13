<?php

class Tenant {
     // Properties
    public $name;
    public $nmb;
    
    function set_nmb($nmb) {
        $this->name = $nmb;
    }
    function get_nmb() {
        return $this->nmb;
    } 
    
    function set_name($name) {
        $this->name = $name;
    }
    function get_name() {
        return $this->name;
    }
}