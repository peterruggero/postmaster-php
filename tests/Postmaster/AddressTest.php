<?php

require_once('BaseTestCase.php');

class AddressTestCase extends PostmasterBaseTestCase
{
    function testValidate()
    {
        $result = Postmaster_AddressValidation::validate(array(
            "company" => "ASLS",
            "contact" => "Joe Smith",
            "street" => array("1110 Algarita Ave."),
            "city" => "Austin",
            "state" => "TX",
            "zip_code" => "78704",
            "country" => "US",
        ));
        $this->assertTrue($result instanceof Postmaster_AddressValidation);

        $resultArray = $result->__toArray();
        $this->assertArrayHasKey('status', $resultArray);
        $this->assertEquals($result->status, 'SUCCESS');
        $this->assertArrayHasKey('addresses', $resultArray);
        $this->assertNotEmpty($resultArray['addresses']);
        
        $this->assertTrue($result->addresses[0] instanceof Postmaster_Address);
        $addressArray = $result->addresses[0]->__toArray();
        $this->assertArrayHasKey('city', $addressArray);
        $this->assertEquals($result->addresses[0]->city, 'Austin');
    }
}
