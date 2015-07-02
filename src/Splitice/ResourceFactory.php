<?php
namespace Splitice;


class ResourceFactory {
    private $db = array();

    /**
     * @var ResourceFactory|null
     */
    private static $instance = null;

    /**
     * @return ResourceFactory
     */
    static function getInstance(){
        if(self::$instance) return self::$instance;
        return self::$instance = new ResourceFactory();
    }

    /**
     * Register a resource at $name, use the function $creation to init
     *
     * @param string $name
     * @param callable $creation
     */
    function register($name, $creation){
        $db = &$this->db;
        $this->db[$name] = function() use($name,$creation,&$db){
            $r = $creation();
            $db[$name] = function() use($r) { return $r; };
            return $r;
        };
    }

    /**
     * Get an object from the resource factory
     *
     * @param $name
     * @return null
     */
    function get($name){
        if(!isset($this->db[$name])){
            return null;
        }
        $r = $this->db[$name];
        return $r();
    }
}