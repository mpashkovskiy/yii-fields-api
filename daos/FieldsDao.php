<?php

class FieldsDao extends CommonDao {

  private $sql_builder;

  function FieldsDao() {
    $this->sql_builder = new SqlBuilder();
  }

  /**
    $fields - array of associative arrays
   
    $fields = [
      {group_id: 'group1', weight: 1, type: 'select', name: 'Марка', values: ['Audi', 'BMW', ...]},
      {...}
    ]
   */
  function saveField($field) {
    $sql = $this->sql_builder->insertField($field);
    $this->execute($sql);
    
    $sql = $this->sql_builder->getFieldId($field);
    $field_type_id = $this->getScalar($sql);
    
    if (isset($field[Field::VALUES])) {
      foreach ($field[Field::VALUES] as $value) {
        $sql = $this->sql_builder->insertAllowedValue($field_type_id, $value);
        $this->execute($sql);
      }
    }
    
    return $this->getField(NULL, $field[Field::NAME]);
  }
  
  function initFields($a_object_id) {
    $sql = $this->sql_builder->insertEmptyValues($a_object_id);
    $this->execute($sql);
  }
  
  function getField($a_object_id, $a_field_name) {
    if ($a_object_id == NULL) {
      $sql = $this->sql_builder->selectField($a_field_name);
      $row = $this->getFirst($sql);
    } else {
      $sql = $this->sql_builder->selectFieldWithValues($a_object_id, $a_field_name);
      $row = $this->getFirst($sql);
      unset($row['id']);
      $row['id'] = $row['ft_id'];
      unset($row['ft_id']);
    }
    if ($row[Field::TYPE] == Field::SELECT_TYPE) {
      $sql = $this->sql_builder->selectAllowedValues($row['id']);
      $row[Field::VALUES] = $this->getColumn($sql);
    }
    return new Field($row);
  }
  
  function getFieldsByNames($a_object_id, $a_names) {
    $names = $a_names;
    if (!is_array($names)) {
      $names = array($names);
    }
    
    $field_object = new FieldsObject();
    $sql = $this->sql_builder->selectFieldsByNames($a_object_id, $names);
    $dataReader = $this->getReader($sql);
    while(($row = $dataReader->read()) !== false) {
      unset($row['id']);
      $row['id'] = $row['ft_id'];
      unset($row['ft_id']);
      $field_object->addField($row);
    }
    return $field_object;
  }
  
  function getFieldsByGroups($a_object_id, $a_group_ids) {
    $group_ids = $a_group_ids;
    if (!is_array($group_ids)) {
      $group_ids = array($group_ids);
    }
    
    $field_object = new FieldsObject();
    $field_object->object_id = $a_object_id;
    $sql = $this->sql_builder->selectFieldsByGroups($a_object_id, $group_ids);
    $dataReader = $this->getReader($sql);
    while(($row = $dataReader->read()) !== false) {
      unset($row['id']);
      $row['id'] = $row['ft_id'];
      unset($row['ft_id']);
      $field_object->addField($row);
    }
    return $field_object;
  }

  function saveValue($a_object_id, $a_field_group, $a_field_name, $a_field_value) {
    $field = array(
      Field::GROUP => $a_field_group,
      Field::NAME => $a_field_name
    );
    $sql = $this->sql_builder->getFieldId($field);
    $field_type_id = $this->getScalar($sql);
    
    $sql = $this->sql_builder->insertFieldValue($a_object_id, $field_type_id, $a_field_value);
    $this->execute($sql);
  }

}

