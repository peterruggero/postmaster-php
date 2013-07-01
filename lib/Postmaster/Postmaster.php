<?php

abstract class Postmaster
{
  public static $apiKey;
  public static $apiBase = 'https://api.postmaster.io';
  const VERSION = '1.2.1';

  public static function getApiKey()
  {
    if (!is_string(self::$apiKey))
      throw new Postmaster_Error('API key not set. Call "Postmaster::setApiKey(<apiKey>);" before using API.');

    return self::$apiKey;
  }

  public static function setApiKey($apiKey)
  {
    self::$apiKey = $apiKey;
  }

}
