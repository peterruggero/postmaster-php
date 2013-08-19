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
  public static function track_external($params)
  {
    Postmaster_ApiResource::_validateParams($params);
    $requestor = new Postmaster_ApiRequestor();
    $response = $requestor->request('post', self::$urlBase, $params);
    return True;
  }
}


class Postmaster_TrackingHistory extends Postmaster_ApiResource
{
}
