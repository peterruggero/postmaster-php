<?php

class Postmaster_Error extends Exception
{
  public function __construct($message=null, $http_status=null, $json_body=null)
  {
    parent::__construct($message);
    $this->http_status = $http_status;
    $this->json_body = $json_body;
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

