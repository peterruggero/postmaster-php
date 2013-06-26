<?php

class Postmaster_Package extends Postmaster_ApiResource
{

  private static $urlBase = '/v1/packages';

  public static function create($params=null)
  {
    $class = get_class();
    Postmaster_ApiResource::_validateParams($params);
    $requestor = new Postmaster_ApiRequestor();
    $response = $requestor->request('post', self::$urlBase, $params);
    return $response['id'];
  }

  public static function all($params=null)
  {
    $class = get_class();
    Postmaster_ApiResource::_validateParams($params);
    $requestor = new Postmaster_ApiRequestor();
    $response = $requestor->request('get', self::$urlBase, $params);

    $results = array();
    foreach($response['results'] as $data) {
      array_push($results, Postmaster_Object::scopedConstructObject($class, $data));
    }
    return $results;
  }

  public static function fit($params=null)
  {
    $requestor = new Postmaster_ApiRequestor();
    $params = json_encode($params);
    $headers = ['Content-Type: application/json'];
    $response = $requestor->request('post', self::$urlBase.'/fit', $params, $headers);
    return $response;
  }
}
