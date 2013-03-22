<?php

require_once('../lib/Postmaster.php');

class PostmasterBaseTestCase extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        Postmaster::setApiKey(getenv('PM_API_KEY'));
        if(getenv('PM_API_HOST')) {
          Postmaster::$apiBase = getenv('PM_API_HOST');
        } else {
          Postmaster::$apiBase = 'https://api.postmaster.io';
        }
    }
    
    function testRequirements()
    {
        $this->assertTrue(function_exists('curl_init'));
        $this->assertTrue(function_exists('json_decode'));
    }
}
