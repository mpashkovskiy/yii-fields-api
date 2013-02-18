<?php

class SqlBuilder {

  const VALUE_TABLE         = 'field_value';
  const TYPE_TABLE          = 'field_type';
  const ALLOWED_VALUE_TABLE = 'field_allowed_value';
  
  function insertField($field) {
    $sql = sprintf(
      'INSERT INTO %s(group_id, weight, type, name, label) VALUES("%s", %d, "%s", "%s", "%s")',
      SqlBuilder::TYPE_TABLE,
      $field[Field::GROUP],
      $field[Field::WEIGHT],
      $field[Field::TYPE],
      $field[Field::NAME],
      $field[Field::LABEL]
    );
    return $sql;
  }
  
  function getFieldId($field) {
    $sql = sprintf(
      'SELECT id FROM %s WHERE %s = "%s" AND %s = "%s"',
      SqlBuilder::TYPE_TABLE,
      Field::GROUP,
      $field[Field::GROUP],
      Field::NAME,
      $field[Field::NAME]
    );
    return $sql;
  }
  
  function insertAllowedValue($field_type_id, $value) {
    $sql = sprintf(
      'INSERT INTO %s(field_type_id, value) VALUES(%s, "%s")', 
      SqlBuilder::ALLOWED_VALUE_TABLE,
      $field_type_id,
      $value
    );
    return $sql;
  }
  
}