<?php

if (!function_exists('curl_init')) {
  throw new Exception('Postmaster needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Postmaster needs the JSON PHP extension.');
}

require(dirname(__FILE__) . '/Postmaster/Postmaster.php');

// Commons
require(dirname(__FILE__) . '/Postmaster/Error.php');
require(dirname(__FILE__) . '/Postmaster/Object.php');
require(dirname(__FILE__) . '/Postmaster/ApiRequestor.php');
require(dirname(__FILE__) . '/Postmaster/ApiResource.php');
require(dirname(__FILE__) . '/Postmaster/Util.php');

// Postmaster API Resources
require(dirname(__FILE__) . '/Postmaster/Address.php');
require(dirname(__FILE__) . '/Postmaster/Package.php');
require(dirname(__FILE__) . '/Postmaster/Shipment.php');
require(dirname(__FILE__) . '/Postmaster/Tracking.php');
require(dirname(__FILE__) . '/Postmaster/TransitTimes.php');
require(dirname(__FILE__) . '/Postmaster/Rates.php');
