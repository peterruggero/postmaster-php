<?php

require_once('./lib/Postmaster.php');
Postmaster::setApiKey("example-api-key");


$result = Postmaster_AddressValidation::validate(array(
  "company" => "ASLS",
  "contact" => "Joe Smith",
  "line1" => "1110 Someplace Ave.",
  "city" => "Austin",
  "state" => "TX",
  "zip_code" => "78704",
  "country" => "US",
));
//var_dump($result);

$result = Postmaster_Shipment::create(array(
  "to" => array(
    "company" => "ASLS",
    "contact" => "Joe Smith",
    "line1" => "1110 Someplace Ave.",
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
));
//var_dump($result);

$sm = Postmaster_Shipment::retrieve(1);
$result = $sm->track();
//var_dump($result);

$sm = Postmaster_Shipment::retrieve(1);
$result = $sm->void();
//var_dump($result);
