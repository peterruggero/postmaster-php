<?php

/*
 * Base class for Postmaster API Objects.
 */
abstract class Postmaster_ApiResource extends Postmaster_Object
{
  /*
   * Get path that should be used to communicate with API.
   */
  public function instanceUrl($base, $action=null)
  {
    $id = $this['id'];
    $class = get_class($this);
    if (!$id) {
      throw new Postmaster_Error("Could not determine which URL to request: $class instance has invalid ID: $id", null);
    }
    $id = Postmaster_ApiRequestor::utf8($id);
    $extn = urlencode($id);
    if ($action) {
      return "$base/$extn/$action";
    }
    return "$base/$extn";
  }

  protected static function _validateParams($params=null)
  {
    if ($params && !is_array($params))
      throw new Postmaster_Error("You must pass an array as the first argument to Postmaster API method calls.");
  }
}
