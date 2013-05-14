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
}


class Postmaster_TrackingHistory extends Postmaster_ApiResource
{
}
