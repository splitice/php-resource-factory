<?php
namespace Splitice;


class ResourceFactory {
    private $db = array();
    private $registration = array();

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
    function register($name, $creation, $validation = null){
        $db = &$this->db;
        $registration = &$this->registration;

        if(!$validation){
            $validation = function(){ return true; };
        }

        $this->registration[$name] = $this->db[$name] = function($validate = true) use($name,$creation,$validation,&$db,&$registration){
            $r = $creation();
            $db[$name] = function() use($r, $name, $validation, &$registration, $validate) {
                if(!$validate || $validation($r)) {
                    return $r;
                }
                $r = $registration[$name];
                $r = $r(false);
                return $r;
            };
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

    /**
     * Clear a resource, i.e so that we can re-connect
     *
     * @param $name
     */
    function clear($name){
        $this->db[$name] = $this->registration[$name];
    }
}