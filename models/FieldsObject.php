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
    if ($data[Field::VALUE] == NULL) {
      $data[Field::VALUE] = Field::VALUE_NOT_SET;
    }
    $this->data[$data[Field::NAME]] = $data;
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
    if ($name == 'attributes') {
      if (!is_array($value))
        return;
      
      $fields = array_keys($this->data);
      foreach ($value as $key => $value) {
        if (!in_array($key, $fields))
          continue;
        
        $this->values[$key] = $value;
      }
    }
  }
  
  function save() {
    $sqlBuilder = new SqlBuilder();
    $fieldsDao = new FieldsDao();
    foreach ($this->values as $key => $value) {
      if (trim($value) == '' || $value == Field::VALUE_NOT_SET)
        continue;
      
      $sql = $sqlBuilder->insertFieldValue($this->object_id, $this->data[$key]['id'], $value);
      $fieldsDao->execute($sql);
    }
  }

}
