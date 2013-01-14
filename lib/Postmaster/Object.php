<?php

class Postmaster_Object implements ArrayAccess
{
  protected $_values;

  // which keys should be converted to Postmaster_Objects
  protected static $obj_keys = array(
      'Postmaster_Shipment.to' => 'Postmaster_Address',
      'Postmaster_Shipment.from_' => 'Postmaster_Address',
      'Postmaster_Shipment.package' => 'Postmaster_Package',
      'Postmaster_Tracking.last_update' => 'DateTime',
      'Postmaster_TrackingHistory.timestamp' => 'DateTime'
    );

  // which keys should be converted to list of Postmaster_Objects
  protected static $obj_list_keys = array(
      'Postmaster_AddressValidation.addresses' => 'Postmaster_Address',
      'Postmaster_Shipment.packages' => 'Postmaster_Package',
      'Postmaster_Tracking.history' => 'Postmaster_TrackingHistory'
    );

  public function __construct($id=null)
  {
    $this->_values = array();
    if ($id)
      $this->id = $id;
  }

  // Standard accessor magic methods
  public function __set($k, $v)
  {
    $this->_values[$k] = $v;
  }
  public function __isset($k)
  {
    return isset($this->_values[$k]);
  }
  public function __unset($k)
  {
    unset($this->_values[$k]);
  }
  public function __get($k)
  {
    if (array_key_exists($k, $this->_values)) {
      return $this->_values[$k];
    } else {
      $class = get_class($this);
      //error_log("Postmaster Notice: Undefined property of $class instance: $k");
      return null;
    }
  }

  // ArrayAccess methods
  public function offsetSet($k, $v)
  {
    $this->$k = $v;
  }

  public function offsetExists($k)
  {
    return array_key_exists($k, $this->_values);
  }

  public function offsetUnset($k)
  {
    unset($this->$k);
  }
  public function offsetGet($k)
  {
    return array_key_exists($k, $this->_values) ? $this->_values[$k] : null;
  }

  public static function scopedConstructObject($class, $values)
  {
    $obj = new $class(isset($values['id']) ? $values['id'] : null);
    $obj->setValues($values);
    return $obj;
  }

  public function setValues($values)
  {
    $this->_values = array();

    foreach ($values as $k => $v) {
      $fullKey = get_class($this) . '.' . $k;
      if (array_key_exists($fullKey, self::$obj_keys)) {
        $class = self::$obj_keys[$fullKey];
        if (!strncmp($class, 'Postmaster', 10)) {
            $this->_values[$k] = self::scopedConstructObject($class, $v);
        } else if ($class == 'DateTime') {
            $result = new DateTime();
            $result->setTimestamp($v);
            $this->_values[$k] = $result;
        }
      } else if (array_key_exists($fullKey, self::$obj_list_keys)) {
        $class = self::$obj_list_keys[$fullKey];
        $result = array();
        foreach ($v as $i)
          array_push($result, self::scopedConstructObject($class, $i));
        $this->_values[$k] = $result;
      } else {
        $this->_values[$k] = $v;
      }
    }
  }

  public function __toJSON()
  {
    if (defined('JSON_PRETTY_PRINT'))
      return json_encode($this->__toArray(), JSON_PRETTY_PRINT);
    else
      return json_encode($this->__toArray());
  }

  public function __toString()
  {
    return $this->__toJSON();
  }

  public function __toArray()
  {   
    $results = array();
    foreach ($this->_values as $k => $v) {
      $fullKey = get_class($this) . '.' . $k;
      if ($v instanceof Postmaster_Object) {
        $results[$k] = $v->__toArray();
      } else if (is_array($v) && array_key_exists($fullKey, self::$obj_list_keys)) {
        $result = array();
        foreach ($v as $i)
          array_push($result, $i->__toArray());
        $results[$k] = $result;
      } else {
        $results[$k] = $v;
      }
    }
    return $results;
  }

}
