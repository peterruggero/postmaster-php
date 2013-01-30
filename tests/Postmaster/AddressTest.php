<?php

require_once('BaseTestCase.php');

class AddressTestCase extends PostmasterBaseTestCase
{
    function testValidateNoChanges()
    {
        $result = Postmaster_AddressValidation::validate(array(
            "company" => "Asls",
            "contact" => "Joe Smith",
            "line1" => "1110 Algarita Ave",
            "city" => "Austin",
            "state" => "TX",
            "zip_code" => "78704",
            "country" => "US",
        ));
        $this->assertTrue($result instanceof Postmaster_AddressValidation);
        $resultArray = $result->__toArray();
        $this->assertArrayHasKey('status', $resultArray);
        $this->assertTrue(isset($result['addresses']));
        $this->assertNotEmpty($result['addresses']);

        $address = $result->addresses[0];
        $addressArray = $address->__toArray();
        $this->assertTrue($address instanceof Postmaster_Address);
        $addressArray = $address->__toArray();
        $this->assertArrayHasKey('zip_code', $addressArray);
        $this->assertEquals('78704', $address->zip_code);
    }
 
    function testValidateInsufficientData()
    {
        $result = Postmaster_AddressValidation::validate(array(
            "company" => "ASLS",
            "contact" => "Joe Smith",
            "line1" => "007 Nowhere Ave",
            "city" => "Austin",
            "state" => "TX",
            "zip_code" => "00001",
            "country" => "US",
        ));
        $this->assertTrue($result instanceof Postmaster_AddressValidation);
        $resultArray = $result->__toArray();
        $this->assertArrayHasKey('status', $resultArray);
        $this->assertEquals('WRONG_ADDRESS', $result->status);
    }
}
