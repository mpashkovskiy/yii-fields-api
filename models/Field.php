<?php

class Field {

  const GROUP       = 'group_id';
  const WEIGHT      = 'weight';
  const TYPE        = 'type';
  const NAME        = 'name';
  const LABEL       = 'label';
  const PREFIX      = 'prefix';
  const SUFFIX      = 'suffix';
  const VALUE       = 'value';
  const VALUES      = 'values';
  const IS_REQUIRED = 'is_required';
  
  const VALUE_NOT_SET = 'Значение не задано';
  
  private $values;
  
  function Field($a_array) {
    $this->values = $a_array;
  }
  
  function get($key) {
    if (array_key_exists($key,$this->values))
          return $this->values[$key];
  }
  
  function set($key, $value) {
    $this->values[$key] = $value;
  }
  
}
