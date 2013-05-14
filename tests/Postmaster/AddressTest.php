<?php

require_once('BaseTestCase.php');

class AddressTestCase extends PostmasterBaseTestCase
{
    function testValidateNoChanges()
    {
        $result = Postmaster_AddressValidation::validate(array(
            "company" => "Postmaster Inc.",
            "contact" => "Joe Smith",
            "line1" => "701 Brazos St. Suite 1616",
            "city" => "Austin",
            "state" => "TX",
            "zip_code" => "78701",
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
        $this->assertEquals('78701', $address->zip_code);
    }
 
     function testValidateAddressAsList()
    {
        $result = Postmaster_AddressValidation::validate(array(
            "company" => "Postmaster Inc.",
            "contact" => "Joe Smith",
            "address" => ["701 Brazos St. Suite 1616"],
            "city" => "Austin",
            "state" => "TX",
            "zip_code" => "78701",
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
        $this->assertEquals('78701', $address->zip_code);
    }
 
    function testValidateInsufficientData()
    {
        try {
            $result = Postmaster_AddressValidation::validate(array(
                "company" => "ASLS",
                "contact" => "Joe Smith",
                "line1" => "007 Nowhere Ave",
                "city" => "Austin",
                "state" => "TX",
                "zip_code" => "00001",
                "country" => "US",
            ));
        }
        catch (InvalidData_Error $expected) {
            $msg = $expected->getMessage();
            $this->assertEquals('Wrong address', $msg);
            return;
        }
    }
}
