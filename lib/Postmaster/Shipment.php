<?php

class Postmaster_Shipment extends Postmaster_ApiResource
{
  private static $urlBase = '/v1/shipments';

  /*
   * Create a new shipment.
   * Arguments:
   *  - to (required) - an array representing the ship-to address: 
   *    - company
   *    - contact
   *    - street - a list of strings defining the street address
   *    - city
   *    - state
   *    - zip
   *  - package (required) - an array (or list of arrays) representing 
   *    the package:
   *    - value
   *    - weight
   *    - dimentions
   *  - from (optional) - an array representing the ship-from address.  
   *    Will use default for account if not provided.
   */
  public static function create($params=null)
  {
    $class = get_class();
    Postmaster_Util::normalizeAddress($params['to']);
    Postmaster_Util::normalizeAddress($params['from_']);
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

  /*
   * Retrieve a package by ID.
   */
  public static function retrieve($id)
  {
    $instance = new Postmaster_Shipment($id);
    $instance->refresh();
    return $instance;
  }

  /*
   * Void a shipment (from an object).
   */
  public function void()
  {
    $requestor = new Postmaster_ApiRequestor();
    $url = $this->instanceUrl(self::$urlBase, 'void');
    $response = $requestor->request('post', $url);
    $this->setValues(array()); //clear
    return $response['message'] == 'OK';
  }

  /*
   * Track a shipment (from an object).
   */
  public function track()
  {
    $requestor = new Postmaster_ApiRequestor();
    $url = $this->instanceUrl(self::$urlBase, 'track');
    $response = $requestor->request('get', $url);

    $results = array();
    foreach($response['results'] as $data) {
      $class = 'Postmaster_Tracking';
      array_push($results, Postmaster_Object::scopedConstructObject($class, $data));
    }
    return $results;
  }
}
