<?php

require_once('BaseTestCase.php');

class ShipmentTestCase extends PostmasterBaseTestCase
{
    private static $sample_shipment = array(
        "to" => array(
            "company" => "ASLS",
            "contact" => "Joe Smith",
            "line1" => "1110 Algarita Ave",
            "city" => "Austin",
            "state" => "TX",
            "zip_code" => "78704-4429",
            "phone_no" => "919-720-7941",
            "country" => "US",
        ),
        "from_" => array(
            "company" => "ASLS",
            "contact" => "Joe Smith",
            "address" => ["1110 Algarita Ave"],
            "city" => "Austin",
            "state" => "TX",
            "zip_code" => "78704-4429",
            "phone_no" => "919-720-7941",
            "country" => "US",
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
        $this->assertArrayHasKey('type', $packageArray);
        $this->assertEquals('CUSTOM', $result->package->type);
        
        $this->assertTrue($result->to instanceof Postmaster_Address);
        $this->assertTrue($result->from instanceof Postmaster_Address);
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
        // label_urls can be different, so ignore it during check
        unset($shipment1Array['package']['label_url']);
        unset($shipment2Array['package']['label_url']);
        unset($shipment1Array['packages'][0]['label_url']);
        unset($shipment2Array['packages'][0]['label_url']);
        $this->assertEquals($shipment1Array, $shipment2Array);
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