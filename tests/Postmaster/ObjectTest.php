<?php

require_once('BaseTestCase.php');

class ObjectTestCase extends PostmasterBaseTestCase
{
    function testSetValues()
    {
        $obj = new Postmaster_Object();
        $obj->setValues(array(
            "param1" => "value1",
            "param2" => "value2",
        ));
        
        $this->assertEquals(TRUE, isset($obj->param1));
        $this->assertEquals(TRUE, isset($obj->param2));
        $this->assertEquals('value1', $obj->param1);
        $this->assertEquals('value2', $obj->param2);

        $this->assertEquals(TRUE, isset($obj['param1']));
        $this->assertEquals(TRUE, isset($obj['param2']));
        $this->assertEquals('value1', $obj['param1']);
        $this->assertEquals('value2', $obj['param2']);
    }
    
    function testGetSetUnset()
    {
        $obj = new Postmaster_Object();
        
        $this->assertEquals(FALSE, isset($obj->param1));
        $this->assertEquals(null, $obj->param1);
        
        $obj->param1 = 'value1';
        $this->assertEquals(TRUE, isset($obj->param1));
        $this->assertEquals('value1', $obj->param1);
        
        unset($obj->param1);
        
        $this->assertEquals(FALSE, isset($obj->param1));
        $this->assertEquals(null, $obj->param1);
    }

    function testOffsetGetSetUnset()
    {
        $obj = new Postmaster_Object();
        
        $this->assertEquals(FALSE, isset($obj['param1']));
        
        $obj['param1'] = 'value1';
        $this->assertEquals(TRUE, isset($obj['param1']));
        $this->assertEquals('value1', $obj['param1']);
        
        unset($obj['param1']);
        
        $this->assertEquals(FALSE, isset($obj['param1']));
    }
    
    function testToJSON()
    {
        $obj = new Postmaster_Object();
        $obj->setValues(array(
            "param1" => "value1",
            "param2" => "value2",
        ));
        $expected = "{\n    \"param1\": \"value1\",\n    \"param2\": \"value2\"\n}";
        $this->assertEquals($expected, $obj->__toJSON());
        $this->assertEquals($expected, (string)$obj);
    }
}
