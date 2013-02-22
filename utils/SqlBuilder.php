<?php

class SqlBuilder {

  const VALUE_TABLE         = 'field_value';
  const TYPE_TABLE          = 'field_type';
  const ALLOWED_VALUE_TABLE = 'field_allowed_value';
  
  const VALUE_NOT_SET = 'Значение не задано';
  
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
  
  function selectFieldsFor($object_id, $group_ids) {
    if ($object_id == NULL) {
      $sql = sprintf(
        'SELECT *, "%s" as value FROM %s ft WHERE ft.group_id IN("%s")',
        SqlBuilder::VALUE_NOT_SET,
        SqlBuilder::TYPE_TABLE,
        implode('", "', $group_ids)
      );
    } else {
      $sql = sprintf(
        'SELECT * FROM %s ft, %s fv WHERE ft.group_id IN("%s") AND ft.id = fv.field_type_id AND fv.object_id = "%s"',
        SqlBuilder::TYPE_TABLE,
        SqlBuilder::VALUE_TABLE,
        implode('", "', $group_ids),
        $object_id
      );
    }
    return $sql;
  }
  
}