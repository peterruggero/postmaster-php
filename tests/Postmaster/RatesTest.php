<?php

require_once('BaseTestCase.php');

class RatesTestCase extends PostmasterBaseTestCase
{
    function testGetRates()
    {
        $result = Postmaster_Rates::get(array(
            "from_zip" => "78701",
            "to_zip" => "78704",
            "weight" => 1.5,
            "carrier" => "fedex",
        ));
        
        $this->assertTrue($result instanceof Postmaster_Rates);
        
        $resultArray = $result->__toArray();
        $this->assertArrayHasKey('currency', $resultArray);
        $this->assertArrayHasKey('charge', $resultArray);
        $this->assertArrayHasKey('service', $resultArray);
        
        $possibleValues = ['GROUND', '3DAY', '2DAY', '2DAY_EARLY', '1DAY', '1DAY_EARLY', '1DAY_MORNING'];
        $this->assertContains($result->service, $possibleValues);
    }
}
