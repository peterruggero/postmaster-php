<?php

class Postmaster_Rates extends Postmaster_ApiResource
{
  private static $urlBase = '/v1/rates';

  /*
   * Ask for the cost to ship a package between two zip codes.
   */ 
  public static function get($params=null)
  {
    $class = get_class();
    Postmaster_ApiResource::_validateParams($params);
    $requestor = new Postmaster_ApiRequestor();
    $response = $requestor->request('post', self::$urlBase, $params);
    return Postmaster_Object::scopedConstructObject($class, $response);
  }
}
