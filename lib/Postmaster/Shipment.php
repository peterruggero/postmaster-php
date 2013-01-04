<?php

class Postmaster_Shipment extends Postmaster_ApiResource
{
  private static $urlBase = '/v1/shipments';

  public static function create($params=null)
  {
    $class = get_class();
    Postmaster_ApiResource::_validateParams($params);
    $requestor = new Postmaster_ApiRequestor();
    $response = $requestor->request('post', self::$urlBase, $params);
    return Postmaster_Object::scopedConstructObject($class, $response);
  }

  public function refresh()
  {
    $requestor = new Postmaster_ApiRequestor();
    $url = $this->instanceUrl(self::$urlBase);
    $response = $requestor->request('get', $url);
    $this->setValues($response);
    return $this;
  }

  public static function retrieve($id)
  {
    $instance = new Postmaster_Shipment($id);
    $instance->refresh();
    return $instance;
  }

  public function void()
  {
    $requestor = new Postmaster_ApiRequestor();
    $url = $this->instanceUrl(self::$urlBase, 'void');
    $response = $requestor->request('post', $url);
    $this->setValues(array()); //clear
    return $response == 'OK';
  }

  public function track()
  {
    $requestor = new Postmaster_ApiRequestor();
    $url = $this->instanceUrl(self::$urlBase, 'track');
    $response = $requestor->request('post', $url);

    $results = array();
    foreach($response['results'] as $data) {
      $class = 'Postmaster_Tracking';
      array_push($results, Postmaster_Object::scopedConstructObject($class, $data));
    }
    return $results;
  }
}
