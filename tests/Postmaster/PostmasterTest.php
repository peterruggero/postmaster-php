<?php

require_once('BaseTestCase.php');

class PostmasterApiKeyTestCase extends PostmasterBaseTestCase
{
    function testNoApiKey()
    {
        $this->setExpectedException(
            'Postmaster_Error', 
            'API key not set. Call "Postmaster::setApiKey(<apiKey>);" before using API.'
        );
        // setUp already set some value, so we have to reset it first
        Postmaster::setApiKey(NULL); 
        Postmaster::getApiKey();
    }
    
    function testApiKey()
    {
        Postmaster::setApiKey("example-api-key");
        $result = Postmaster::getApiKey();
        $expected = "example-api-key";
        $this->assertEquals($expected, $result);
    }
}
