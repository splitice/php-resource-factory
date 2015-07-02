<?php

use Splitice\ResourceFactory;

class ResourceFactoryTest extends PHPUnit_Framework_TestCase {
    function testSimple(){
        ResourceFactory::getInstance()->register('test1',function(){return 1;});
        $this->assertEquals(1,ResourceFactory::getInstance()->get('test1'));
    }
    function testOneInstance(){
        ResourceFactory::getInstance()->register('test1',function(){return new \stdClass();});
        $this->assertSame(ResourceFactory::getInstance()->get('test1'),ResourceFactory::getInstance()->get('test1'));
    }
}
