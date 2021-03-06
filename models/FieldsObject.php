<?php

class FieldsObject {

  private $errors;
  private $data;
  private $values;
  
  public $object_id;
  
  function __construct() {
    $this->data = array();
    $this->errors = array();
    $this->values = array();
  }
  
  function addField($data) {
    $this->data[$data[Field::NAME]] = $data;
  }
  
  function getGroupNames() {
    $groups = array();
    foreach ($this->data as $name => $field) {
      if (!in_array($field[Field::GROUP], $groups))
        $groups[] = $field[Field::GROUP];
    }
    return $groups;
  }
  
  function getGroupFields($a_group_name) {
    $group_name = $a_group_name;
    if (!is_array($group_name)) {
      $group_name = array($group_name);
    }
    $group = array();
    foreach ($this->data as $name => $field) {
      if (in_array($field[Field::GROUP], $group_name)) {
        $group[] = $field;
      }
    }
    return $group;
  }
  
  function getAttribute($name) {
    return $this->data[$name];
  }
  
  function removeAttribute($name) {
    unset($this->data[$name]);
  }
  
  function getAttributes() {
    return $this->data;
  }
  
  function getAttributeLabel($attribute) {
    return $this->data[$attribute][Field::LABEL];
  }
  
  function isAttributeRequired($attribute) {
    return $this->data[$attribute][Field::IS_REQUIRED];
  }
  
  function hasErrors() {
    return (count($this->errors) == 0);
  }
  
  function getError($attribute) {
    return $this->errors[$attribute];
  }
  
  function getErrors() {
    return $this->errors;
  }

  function getValidators($attribute=null) {
    return array();
  }

  function __set($name, $value) {
    $fields = array_keys($this->data);
    if ($name == 'attributes') {
      if (!is_array($value))
        return;
      
      foreach ($value as $key => $value) {
        if (!in_array($key, $fields))
          continue;
        
        $this->values[$key] = $value;
      }
    } else if (in_array($name, $fields)) {
      $this->values[$name] = $value;
    }
  }
  
  function save() {
    $sqlBuilder = new SqlBuilder();
    $fieldsDao = new FieldsDao();
    foreach ($this->values as $key => $value) {
      $value = trim($value);
      
      $sql = $sqlBuilder->insertFieldValue($this->object_id, $this->data[$key]['id'], $value);
      $fieldsDao->execute($sql);
    }
  }

}
