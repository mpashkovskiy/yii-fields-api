<?php

class SqlBuilder {

  const VALUE_TABLE         = 'field_value';
  const TYPE_TABLE          = 'field_type';
  const ALLOWED_VALUE_TABLE = 'field_allowed_value';

  function insertField($field) {
    return sprintf(
      'INSERT INTO %s(group_id, weight, type, is_required, name, label, prefix, suffix) 
               VALUES("%s",     %d,     "%s", %d,          "%s", "%s",  "%s",   "%s")',
      SqlBuilder::TYPE_TABLE,
      $field['group_id'],
      $field['weight'],
      $field['type'],
      $field['is_required'],
      $field['name'],
      $field['label'],
      $field['prefix'],
      $field['suffix']
    );
  }
  
  function getFieldId($field) {
    return sprintf(
      'SELECT id FROM %s WHERE %s = "%s"',
      SqlBuilder::TYPE_TABLE,
      'name',
      $field['name']
    );
  }
  
  /*function selectFieldId($a_field_name) {
    return sprintf(
      'SELECT id FROM %s WHERE name = "%s"',
      SqlBuilder::TYPE_TABLE,
      $a_field_name
    );
  }*/
  
  function insertAllowedValue($field_type_id, $value, $is_default) {
    return sprintf(
      'INSERT INTO %s(field_type_id, value, is_default) VALUES(%s, "%s", %d)', 
      SqlBuilder::ALLOWED_VALUE_TABLE,
      $field_type_id,
      $value,
      $is_default
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
  
  function selectAllFields($object_id) {
    return sprintf(
      'SELECT *, ft.id as ft_id
       FROM %s ft, %s fv
       WHERE ft.id = fv.field_type_id AND fv.object_id = "%s"',
      SqlBuilder::TYPE_TABLE,
      SqlBuilder::VALUE_TABLE,
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
      'SELECT value FROM %s WHERE field_type_id = %d',
      SqlBuilder::ALLOWED_VALUE_TABLE,
      $a_field_id
    );
  }
  
  function insertEmptyValues($a_object_id, $a_default_values) {
    $values_part = array();
    foreach ($a_default_values as $field_id => $value) {
      $values_part[] = sprintf('(%d,           %d,        "%s",  NOW())',
                                 $a_object_id, $field_id, $value);
    }
    $values_part = implode(', ', $values_part); 
    return sprintf(
      'INSERT INTO %s(object_id, field_type_id, value, changed_at) VALUES' . $values_part,
      SqlBuilder::VALUE_TABLE
    );
  }
  
  function insertFieldValue($a_object_id, $a_field_id, $a_value) {
    return sprintf(
      'UPDATE %s SET value = "%s", changed_at = NOW() WHERE object_id = "%s" AND field_type_id = %d',
      SqlBuilder::VALUE_TABLE,
      $a_value,
      $a_object_id,
      $a_field_id
    );
  }
  
  function deleteAllValues($a_object_id) {
    return sprintf(
      'DELETE FROM %s WHERE object_id = "%s"',
      SqlBuilder::VALUE_TABLE,
      $a_object_id
    );
  }
  
  function deleteEmptyValues($a_object_id) {
    return sprintf(
      'DELETE FROM %s WHERE object_id = "%s" && value = ""',
      SqlBuilder::VALUE_TABLE,
      $a_object_id
    );
  }
  
  function selectFieldNames() {
    return sprintf('SELECT id, name FROM %s', SqlBuilder::TYPE_TABLE);
  }
  
  function selectDefaultValues() {
    return sprintf(
      'SELECT field_type_id, value FROM %s WHERE is_default = 1',
      SqlBuilder::ALLOWED_VALUE_TABLE
    );
  }
  
}
