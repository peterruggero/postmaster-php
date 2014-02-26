<?php

class Postmaster_TransitTime extends Postmaster_ApiResource
{
}

class Postmaster_TransitTimes extends Postmaster_ApiResource
{
  private static $urlBase = '/v1/times';

  /*
   * Ask for the time to transport a shipment between two zip codes.
   */ 
  public static function get($params=null)
  {
    $class = get_class();
    Postmaster_ApiResource::_validateParams($params);
    $requestor = new Postmaster_ApiRequestor();
    $response = $requestor->request('post', self::$urlBase, $params);
    
    $results = array();
    foreach($response['services'] as $data) {
      $class = 'Postmaster_TransitTime';
      array_push($results, Postmaster_Object::scopedConstructObject($class, $data));
    }
    return $results;
  }
}
