<?php

/*
 * Communication to API over HTTP.
 */
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

  public function request($meth, $url, $params=null, $headers=null)
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
    $allHeaders = array(
        'X-Postmaster-Client-User-Agent: ' . json_encode($ua),
        'User-Agent: Postmaster/v1 PhpBindings/' . Postmaster::VERSION
    );
    if ($headers)
        $allHeaders = array_merge($allHeaders, $headers);

    list($rbody, $rcode) = $this->_curlRequest($meth, $absUrl, $allHeaders, $params, $apiKey);

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
      if (is_array($resp) && array_key_exists('message', $resp)) {
        $msg = $resp['message'];
      } else {
        $msg = "Unknown API error";
      }
      if ($rcode == 400) {
        throw new InvalidData_Error($msg, $rbody, $rcode, $resp);
      } else if ($rcode == 401) {
        throw new Authentication_Error($msg, $rbody, $rcode, $resp);
      } else if ($rcode == 403) {
        throw new Permission_Error($msg, $rbody, $rcode, $resp);
      } 
      throw new API_Error($msg, $rbody, $rcode, $resp);
    }
    return $resp;
  }

  private function _curlRequest($meth, $absUrl, $headers, $params, $apiKey)
  {
    $curl = curl_init();
    $opts = array();
    if ($meth == 'get') {
      $opts[CURLOPT_HTTPGET] = 1;
      if (!is_string($params) && count($params) > 0) {
        $encoded = http_build_query($params);
        $absUrl = "$absUrl?$encoded";
      } elseif (is_string($params) && strlen($params) > 0) {
        $absUrl = "$absUrl?$params";
      }
    } else if ($meth == 'post') {
      $opts[CURLOPT_POST] = 1;
      if (!is_string($params)) {
        $opts[CURLOPT_POSTFIELDS] = http_build_query($params);
      } else {
        $opts[CURLOPT_POSTFIELDS] = $params;
      }

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

    if ($apiKey)
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
    throw new Network_Error($msg);
  }
}
