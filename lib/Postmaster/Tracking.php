<?php

class Postmaster_Tracking extends Postmaster_ApiResource
{
  private static $urlBase = '/v1/track';

  /*
   * Track a package by carrier waybill (tracking number).
   */ 
  public static function track($tracking_id)
  {
    $class = get_class();
    $requestor = new Postmaster_ApiRequestor();
    $response = $requestor->request('get', self::$urlBase.'?tracking='.$tracking_id);
    return Postmaster_Object::scopedConstructObject($class, $response);
  }

  /*
   * This allows you to monitor the status of packages that you created outside
   * of Postmaster.
   */
  public static function monitor_external($params)
  {
    Postmaster_ApiResource::_validateParams($params);
    $requestor = new Postmaster_ApiRequestor();
    $response = $requestor->request('post', self::$urlBase, $params);
    return $response;
  }

  /**
   * Converts JSON response to Postmaster_Object/ExternalPackage. 
   * This is a helper function for converting the initially returned 
   * hook response if anything exists. All other responses should be 
   * handled by the user's URL.
   * @param  String $response JSON string.
   * @return Postmaster_Object           Postmaster_Object result.
   */
  public static function toPostmaster_ExternalPackage($response)
  {
    $class = 'Postmaster_ExternalPackage';
    return Postmaster_Object::scopedConstructObject($class, $response);
  }
}


class Postmaster_ExternalPackage extends Postmaster_ApiResource
{
}

class Postmaster_TrackingHistory extends Postmaster_ApiResource
{
}
