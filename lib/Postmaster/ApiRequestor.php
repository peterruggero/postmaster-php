<?php

class Postmaster_ApiRequestor
{
  public static function apiUrl($url='')
  {
    $apiBase = Postmaster::$apiBase;
    return "$apiBase$url";
  }

  public static function utf8($value)
  {
    if (is_string($value))
      return utf8_encode($value);
    else
      return $value;
  }

  public static function post($url, $params) {
    if(empty($params)) {
      $params = array();
    }
    return $return;
  }

  public function request($meth, $url, $params=null)
  {
    $absUrl = self::apiUrl($url);
    $apiKey = Postmaster::getApiKey();

    if (!$params)
      $params = array();

    $ua = array(
      'bindings_version' => Postmaster::VERSION,
      'lang' => 'php',
      'lang_version' => phpversion(),
      'publisher' => 'stripe',
      'uname' => php_uname()
    );
    $headers = array(
        'X-Postmaster-Client-User-Agent: ' . json_encode($ua),
	'User-Agent: Postmaster/v1 PhpBindings/' . Postmaster::VERSION
    );

    list($rbody, $rcode) = $this->_curlRequest($meth, $absUrl, $headers, $params, $apiKey);

    if ($rbody == 'OK') {
      $resp = $rbody;
    } else {
      try {
        $resp = json_decode($rbody, true);
      } catch (Exception $e) {
        throw new Postmaster_Error("Invalid response body from API: $rbody (HTTP response code was $rcode)", $rcode, $rbody);
      }
    }

    if ($rcode < 200 || $rcode >=300) {
      if (is_array($resp) && array_key_exists('msg', $resp)) {
        $msg = $resp['msg'];
      } else {
        $msg = "Unknown API error";
      }
      throw new Postmaster_Error($msg, $rcode);
    }
    return $resp;
  }

  private function _curlRequest($meth, $absUrl, $headers, $params, $apiKey)
  {
    $curl = curl_init();
    $opts = array();
    if ($meth == 'get') {
      $opts[CURLOPT_HTTPGET] = 1;
      if (count($params) > 0) {
	$encoded = http_build_query($params);
	$absUrl = "$absUrl?$encoded";
      }
    } else if ($meth == 'post') {
      $opts[CURLOPT_POST] = 1;
      $opts[CURLOPT_POSTFIELDS] = http_build_query($params);
    } else {
      throw new Postmaster_Error("Unrecognized method $meth");
    }

    $absUrl = self::utf8($absUrl);
    $opts[CURLOPT_URL] = $absUrl;
    $opts[CURLOPT_RETURNTRANSFER] = true;
    $opts[CURLOPT_CONNECTTIMEOUT] = 30;
    $opts[CURLOPT_TIMEOUT] = 80;
    $opts[CURLOPT_RETURNTRANSFER] = true;
    $opts[CURLOPT_HTTPHEADER] = $headers;

    $opts[CURLOPT_USERPWD] = $apiKey . ":";

    curl_setopt_array($curl, $opts);
    $rbody = curl_exec($curl);
    if ($rbody === false) {
      $errno = curl_errno($curl);
      $message = curl_error($curl);
      curl_close($curl);
      $this->handleCurlError($errno, $message);
    }

    $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return array($rbody, $rcode);
  }

 public function handleCurlError($errno, $message)
  {
    $apiBase = Postmaster::$apiBase;
    switch ($errno) {
      case CURLE_COULDNT_CONNECT:
      case CURLE_COULDNT_RESOLVE_HOST:
      case CURLE_OPERATION_TIMEOUTED:
        $msg = "Could not connect to Postmaster ($apiBase). Please check your internet connection and try again. If this problem persists, let us know at support@postmaster.io.";
        break;
      case CURLE_SSL_CACERT:
      case CURLE_SSL_PEER_CERTIFICATE:
        $msg = "Could not verify Postmaster's SSL certificate. If this problem persists, let us know at support@postmaster.io.";
        break;
      default:
        $msg = "Unexpected error communicating with Postmaster. If this problem persists, let us know at support@postmaster.io.";
    }

    $msg .= "\n\n(Network error [errno $errno]: $message)";
    throw new Postmaster_Error($msg);
  }
}
