<?php

class Postmaster_Error extends Exception
{
  public function __construct($message=null, $http_body=null, $http_status=null, $json_body=null)
  {
    parent::__construct($message);
    $this->http_body = $http_body;
    $this->http_status = $http_status;
    $this->json_body = $json_body;
  }
  
  public function getHttpBody()
  {
    return $this->http_body;
  }
  
  public function getHttpStatus()
  {
    return $this->http_status;
  }

  public function getJsonBody()
  {
    return $this->json_body;
  }
}

/*
 * An error with the Postmaster API, 500 or similar.
 */
class API_Error extends Postmaster_Error
{
}

/*
 * An error with your network connectivity.
 */
class Network_Error extends Postmaster_Error
{
}

/*
 * 401 style error.
 */
class Authentication_Error extends Postmaster_Error
{
}

/*
 * 403 style error.
 */
class Permission_Error extends Postmaster_Error
{
}

/*
 * 400 style error.
 */    
class InvalidData_Error extends Postmaster_Error
{
}