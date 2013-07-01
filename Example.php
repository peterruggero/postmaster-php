<?php
/* at startup set API key */
require_once('./lib/Postmaster.php');
Postmaster::setApiKey("example-api-key");

/* at first validate recipient address */
$result = Postmaster_AddressValidation::validate(array(
  "company" => "Postmaster Inc.",
  "contact" => "Joe Smith",
  "line1" => "701 Brazos St. Suite 1616",
  "city" => "Austin",
  "state" => "TX",
  "zip_code" => "78701",
  "country" => "US",
));
//var_dump($result);

/* if address is ok you can ask for time and rates for it */
$result = Postmaster_TransitTimes::get(array(
    "from_zip" => "78701",
    "to_zip" => "78704",
    "weight" => 1.5,
    "carrier" => "fedex",
));
//var_dump($result);

$result = Postmaster_Rates::get(array(
    "from_zip" => "78701",
    "to_zip" => "78704",
    "weight" => 1.5,
    "carrier" => "fedex",
));
//var_dump($result);

/* when user will choose delivery type you create shipment */ 
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
  "carrier" => "fedex",
  "service" => "2DAY",
  "package" => array(
    "weight" => 1.5,
    "length" => 10,
    "width" => 6,
    "height" => 8,
  ),
));
//var_dump($result);

/* store in your DB shipment ID for later use */
$shipment_id = $result->id;

/* anytime you can extract shipment data */
$sm = Postmaster_Shipment::retrieve($shipment_id);
//var_dump($sm);

/* or check delivery status */ 
$result = $sm->track();
//var_dump($result);

/* you can cancel shipment, but only before being picked up by the carrier */
$result = $sm->void();
//var_dump($result);

/* create box example */
$result = Postmaster_Package::create(array(
    "width" => 10,
    "height" => 12,
    "length" => 8,
    "name" => 'My Box'
));
var_dump($result);

/* list boxes example */
$result = Postmaster_Package::all(array(
    "limit" => 2
));
var_dump($result);

/* fit items in box example */
$result = Postmaster_Package::fit(array(
    "items" => array(
        array("width" => 2.2, "length" => 3, "height" => 1, "count" => 2),
    ),
    "packages" => array(
        array("width" => 6, "length" => 6, "height" => 6, "sku" => "123ABC"),
        array("width" => 12, "length" => 12, "height" => 12, "sku" => "456XYZ"),
    ),
));
var_dump($result);
