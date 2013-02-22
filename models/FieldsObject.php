<?php

class FieldsObject {

  public $errors;
  
  private $data;
  
  function FieldObject() {
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

}