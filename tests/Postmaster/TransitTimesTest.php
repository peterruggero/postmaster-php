<?php

require_once('BaseTestCase.php');

class TransitTimesTestCase extends PostmasterBaseTestCase
{
    function testGetTimes()
    {
        $result = Postmaster_TransitTimes::get(array(
            "from_zip" => "78701",
            "to_zip" => "78704",
            "weight" => 1.5,
            "carrier" => "fedex",
        ));
        
        $this->assertTrue(is_array($result));
        $this->assertNotEmpty($result);
        $this->assertTrue($result[0] instanceof Postmaster_TransitTime);
        
        $resultArray = $result[0]->__toArray();
        $this->assertArrayHasKey('delivery_timestamp', $resultArray);
        $this->assertArrayHasKey('service', $resultArray);
        $this->assertTrue($result[0]->delivery_timestamp instanceof DateTime);
        
        $possibleValues = ['GROUND', '3DAY', '2DAY', '2DAY_EARLY', '1DAY', '1DAY_EARLY', '1DAY_MORNING'];
        $this->assertContains($result[0]->service, $possibleValues);
    }
}
