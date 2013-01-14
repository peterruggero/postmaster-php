<?php

require_once('BaseTestCase.php');

class ShipmentTestCase extends PostmasterBaseTestCase
{
    private static $sample_shipment = array(
        "to" => array(
            "company" => "ASLS",
            "contact" => "Joe Smith",
            "street" => array("1110 Algarita Ave."),
            "city" => "Austin",
            "state" => "TX",
            "zip_code" => "78704",
            "phone_no" => "919-720-7941",
        ),
        "carrier" => "ups",
        "service" => "2DAY",
        "package" => array(
            "weight" => 1.5,
            "length" => 10,
            "width" => 6,
            "height" => 8,
        ),
    );
      
    function testCreate()
    {
        $result = Postmaster_Shipment::create(self::$sample_shipment);
        $this->assertTrue($result instanceof Postmaster_Shipment);

        $resultArray = $result->__toArray();
        $this->assertArrayHasKey('status', $resultArray);
        $this->assertEquals('Processing', $result->status);
        $this->assertArrayHasKey('package', $resultArray);
        
        $this->assertTrue($result->package instanceof Postmaster_Package);
        $packageArray = $result->package->__toArray();
        $this->assertArrayHasKey('type_', $packageArray);
        $this->assertEquals('CUSTOM', $result->package->type_);
        
        $this->assertTrue($result->to instanceof Postmaster_Address);
        $this->assertTrue($result->from_ instanceof Postmaster_Address);
    }

    function testInvalidRefreshe()
    {
        $this->setExpectedException('Postmaster_Error');
        $shipment = new Postmaster_Shipment();
        $shipment->refresh();
    }
    
    function testCreateRetrive()
    {
        $shipment1 = Postmaster_Shipment::create(self::$sample_shipment);
        $shipment2 = Postmaster_Shipment::retrieve($shipment1->id);
        
        $this->assertTrue($shipment2 instanceof Postmaster_Shipment);
        $shipment1Array = $shipment1->__toArray();
        $shipment2Array = $shipment2->__toArray();
        $this->assertEquals($shipment1, $shipment2);
    }

    function testCreateTrack()
    {
        $shipment = Postmaster_Shipment::create(self::$sample_shipment);
        $result = $shipment->track();
        
        $this->assertTrue(is_array($result));
        $this->assertNotEmpty($result);
        $this->assertTrue($result[0] instanceof Postmaster_Tracking);
        
        $resultArray = $result[0]->__toArray();
        $this->assertArrayHasKey('status', $resultArray);
        $this->assertArrayHasKey('history', $resultArray);
        $this->assertNotEmpty($result[0]->history);
        $this->assertTrue($result[0]->history[0] instanceof Postmaster_TrackingHistory);
    }
    
    function testCreateVoid()
    {
        $shipment = Postmaster_Shipment::create(self::$sample_shipment);
        $result = $shipment->void();
        
        $this->assertTrue(is_bool($result));
        $this->assertEquals(true, $result);
    }
}
