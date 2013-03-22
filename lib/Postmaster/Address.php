<?php

class Postmaster_Address extends Postmaster_ApiResource
{
}

class Postmaster_AddressValidation extends Postmaster_ApiResource
{
  private static $urlBase = '/v1/validate';

  /*
   * Validate that an address is correct.
   */ 
  public static function validate($params=null)
  {
    $class = get_class();
    Postmaster_ApiResource::_validateParams($params);
    Postmaster_Util::normalizeAddress($params);
    $requestor = new Postmaster_ApiRequestor();
    $response = $requestor->request('post', self::$urlBase, $params);
    return Postmaster_Object::scopedConstructObject($class, $response);
  }
}

