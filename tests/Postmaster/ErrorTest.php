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
            
            $json_body = $expected->getJsonBody();
            $this->assertArrayHasKey("message", $json_body);
            $this->assertArrayHasKey("code", $json_body);
            
            $http_body = $expected->getHttpBody();
            $this->assertStringStartsWith('{', $http_body);
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
        catch (Network_Error $expected) {
            $msg = $expected->getMessage();
            $this->assertStringStartsWith('Could not connect', $msg);
            return;
        }
 
        $this->fail('An expected exception has not been raised.');
    }
    
    function testUnexpectedNetworkError()
    {
        Postmaster::$apiBase = 'ssh://do-not-exist';
        try {
            $result = Postmaster_AddressValidation::validate(array());
        }
        catch (Network_Error $expected) {
            $msg = $expected->getMessage();
            $this->assertStringStartsWith('Unexpected error', $msg);
            return;
        }
 
        $this->fail('An expected exception has not been raised.');
    }

    function testInvalidAuthHeaderError()
    {
        Postmaster::setApiKey("incorrect-api-key");
        try {
            $result = Postmaster_AddressValidation::validate(array());
        }
        catch (Permission_Error $expected) {
            $msg = $expected->getMessage();
            $this->assertStringStartsWith('Invalid authorization header', $msg);
            return;
        }
 
        $this->fail('An expected exception has not been raised.');
    }

    function testInvalidAuthError()
    {
        $apiKey = Postmaster::getApiKey();
        Postmaster::setApiKey(substr($apiKey, 0, -1));
        try {
            $result = Postmaster_AddressValidation::validate(array());
        }
        catch (Permission_Error $expected) {
            $msg = $expected->getMessage();
            $this->assertStringStartsWith('Invalid authorization', $msg);
            return;
        }
 
        $this->fail('An expected exception has not been raised.');
    }
    
    function testNoAuthError()
    {
        Postmaster::setApiKey('');
        try {
            $result = Postmaster_AddressValidation::validate(array());
        }
        catch (Authentication_Error $expected) {
            $msg = $expected->getMessage();
            $this->assertStringStartsWith('You must authorize', $msg);
            return;
        }
 
        $this->fail('An expected exception has not been raised.');
    }
}
