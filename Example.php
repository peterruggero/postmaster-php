<?php

require_once('./lib/Postmaster.php');
Postmaster::setApiKey("example-api-key");


$result = Postmaster_AddressValidation::validate(array(
  "company" => "Postmaster Inc.",
  "contact" => "Joe Smith",
  "line1" => "701 Brazos St. Suite 1616",
  "city" => "Austin",
  "state" => "TX",
  "zip_code" => "78701",
  "country" => "US",
));
var_dump($result);

$result = Postmaster_Shipment::create(array(
  "to" => array(
    "company" => "Postmaster Inc.",
    "contact" => "Joe Smith",
    "line1" => "701 Brazos St. Suite 1616",
    "city" => "Austin",
    "state" => "TX",
    "zip_code" => "78701",
    "phone_no" => "512-693-4040",
  ),
  "from" => array(
    "company" => "Postmaster Inc.",
    "contact" => "Joe Smith",
    "line1" => "701 Brazos St. Suite 1616",
    "city" => "Austin",
    "state" => "TX",
    "zip_code" => "78701",
    "phone_no" => "512-693-4040",
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

$shipment_id = $result->id;

$sm = Postmaster_Shipment::retrieve($shipment_id);
$result = $sm->track();
//var_dump($result);

$result = $sm->void();
//var_dump($result);
