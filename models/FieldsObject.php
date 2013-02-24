<?php

class FieldsObject {

  private $errors;
  
  private $data;
  
  function __construct() {
    $this->data = array();
    $this->errors = array();
  }
  
  function addField($data) {
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

}
