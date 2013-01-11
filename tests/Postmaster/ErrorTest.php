<?php

require_once('BaseTestCase.php');

class ErrorTestCase extends PostmasterBaseTestCase
{
    function testAPIError()
    {
        try {
            $result = Postmaster_AddressValidation::validate(array());
        }
        catch (Postmaster_Error $expected) {
            $this->assertEquals(500, $expected->getHttpStatus());
            $body = $expected->getJsonBody();
            $this->assertArrayHasKey("msg", $body);
            $this->assertArrayHasKey("code", $body);
            return;
        }
 
        $this->fail('An expected exception has not been raised.');
    }
    
    function testNetworkError()
    {
        Postmaster::$apiBase = 'http://do-not-exist';
        try {
            $result = Postmaster_AddressValidation::validate(array());
        }
        catch (Postmaster_Error $expected) {
            $msg = $expected->getMessage();
            $this->assertStringStartsWith('Could not connect', $msg);
            return;
        }
 
        $this->fail('An expected exception has not been raised.');
    }
    
    function testOtherError()
    {
        Postmaster::$apiBase = 'ssh://do-not-exist';
        try {
            $result = Postmaster_AddressValidation::validate(array());
        }
        catch (Postmaster_Error $expected) {
            $msg = $expected->getMessage();
            $this->assertStringStartsWith('Unexpected error', $msg);
            return;
        }
 
        $this->fail('An expected exception has not been raised.');
    }
}
