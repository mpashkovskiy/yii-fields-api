<?php

class SqlBuilder {

  const VALUE_TABLE         = 'field_value';
  const TYPE_TABLE          = 'field_type';
  const ALLOWED_VALUE_TABLE = 'field_allowed_value';

  function insertField($field) {
    return sprintf(
      'INSERT INTO %s(group_id, weight, type, name, label) VALUES("%s", %d, "%s", "%s", "%s")',
      SqlBuilder::TYPE_TABLE,
      $field[Field::GROUP],
      $field[Field::WEIGHT],
      $field[Field::TYPE],
      $field[Field::NAME],
      $field[Field::LABEL]
    );
  }
  
  function getFieldId($field) {
    return sprintf(
      'SELECT id FROM %s WHERE %s = "%s" AND %s = "%s"',
      SqlBuilder::TYPE_TABLE,
      Field::GROUP,
      $field[Field::GROUP],
      Field::NAME,
      $field[Field::NAME]
    );
  }
  
  /*function selectFieldId($a_field_name) {
    return sprintf(
      'SELECT id FROM %s WHERE name = "%s"',
      SqlBuilder::TYPE_TABLE,
      $a_field_name
    );
  }*/
  
  function insertAllowedValue($field_type_id, $value) {
    return sprintf(
      'INSERT INTO %s(field_type_id, value) VALUES(%s, "%s")', 
      SqlBuilder::ALLOWED_VALUE_TABLE,
      $field_type_id,
      $value
    );
  }
  
  function selectField($a_field_name) {
    return sprintf(
      'SELECT * FROM %s WHERE name = "%s"',
      SqlBuilder::TYPE_TABLE,
      $a_field_name
    );
  }
  
  function selectFieldWithValues($object_id, $a_field_name) {
    return sprintf(
      'SELECT *, ft.id as ft_id
       FROM %s ft, %s fv
       WHERE ft.name = "%s" AND ft.id = fv.field_type_id AND fv.object_id = "%s"',
      SqlBuilder::TYPE_TABLE,
      SqlBuilder::VALUE_TABLE,
      $a_field_name,
      $object_id
    );
  }
  
  function selectFieldsByNames($object_id, $fields_ids) {
    return sprintf(
      'SELECT *, ft.id as ft_id
       FROM %s ft, %s fv
       WHERE ft.name IN("%s") AND ft.id = fv.field_type_id AND fv.object_id = "%s"',
      SqlBuilder::TYPE_TABLE,
      SqlBuilder::VALUE_TABLE,
      implode('", "', $fields_ids),
      $object_id
    );
  }
  
  function selectFieldsByGroups($object_id, $group_ids) {
    return sprintf(
      'SELECT *, ft.id as ft_id
       FROM %s ft, %s fv
       WHERE ft.group_id IN("%s") AND ft.id = fv.field_type_id AND fv.object_id = "%s"',
      SqlBuilder::TYPE_TABLE,
      SqlBuilder::VALUE_TABLE,
      implode('", "', $group_ids),
      $object_id
    );
  }
  
  function selectAllowedValues($a_field_id) {
    return sprintf(
      'SELECT value FROM %s WHERE field_type_id = %d ORDER BY value',
      SqlBuilder::ALLOWED_VALUE_TABLE,
      $a_field_id
    );
  }
  
  function insertEmptyValues($a_object_id) {
    return sprintf(
      'INSERT INTO %s(object_id, field_type_id)
       SELECT %d, id
       FROM %s',
      SqlBuilder::VALUE_TABLE,
      $a_object_id,
      SqlBuilder::TYPE_TABLE
    );
  }
  
  function insertFieldValue($a_object_id, $a_field_id, $a_value) {
    return sprintf(
      'UPDATE %s SET value = "%s" WHERE object_id = "%s" AND field_type_id = %d',
      SqlBuilder::VALUE_TABLE,
      $a_value,
      $a_object_id, $a_field_id
    );
  }
  
}
