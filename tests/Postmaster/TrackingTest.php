<?php

require_once('BaseTestCase.php');

class TrackingTestCase extends PostmasterBaseTestCase
{
    function testTrackByReference()
    {
        $result = Postmaster_Tracking::track('1ZW470V80310800043');

        $this->assertTrue($result instanceof Postmaster_Tracking);

        $resultArray = $result->__toArray();
        $this->assertArrayHasKey('status', $resultArray);
        $this->assertArrayHasKey('history', $resultArray);
        $this->assertNotEmpty($result->history);
        $this->assertTrue($result->history[0] instanceof Postmaster_TrackingHistory);
    }

    function testTrackExternal()
    {
        $result = Postmaster_Tracking::monitor_external(array(
            "tracking_no" => "1ZW470V80310800043",
            "url" => "http://example.com/your-http-post-listener",
            "events" => ["Delivered", "Exception"]
        ));

        $this->assertTrue(is_bool($result));
        $this->assertTrue($result);
    }
}
