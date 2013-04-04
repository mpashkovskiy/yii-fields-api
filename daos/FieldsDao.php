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
    
    if (isset($field['values'])) {
      foreach ($field['values'] as $value) {
        $is_default = ($value == $field['default_value']) ? 1 : 0;
        $sql = $this->sql_builder->insertAllowedValue($field_type_id, $value, $is_default);
        $this->execute($sql);
      }
    }
    
    return $this->getField(NULL, $field[Field::NAME]);
  }
  
  function initFields($a_object_id) {
    // get all fields
    $sql = $this->sql_builder->selectFieldNames();
    $rows = $this->getAll($sql);
    $values = array();
    foreach ($rows as $row) {
      $values[$row['id']] = '';
      if ($row['name'] == 'city') {
        $values[$row['id']] = 'Санкт-Петербург';
      }
    }
    
    // set defaults!
    $sql = $this->sql_builder->selectDefaultValues();
    $rows = $this->getAll($sql);
    foreach ($rows as $row) {
      $values[$row['field_type_id']] = $row['value'];
    }
    
    $sql = $this->sql_builder->insertEmptyValues($a_object_id, $values);
    $this->execute($sql);
  }
  
  function getAllFields($a_object_id) {
    $field_object = new FieldsObject();
    $field_object->object_id = $a_object_id;
    $sql = $this->sql_builder->selectAllFields($a_object_id);
    $dataReader = $this->getReader($sql);
    while(($row = $dataReader->read()) !== false) {
      unset($row['id']);
      $row['id'] = $row['ft_id'];
      unset($row['ft_id']);
      
      if (in_array($row['type'], array('select', 'checkbox-group'))) {
        $sql = $this->sql_builder->selectAllowedValues($row['id']);
        $row['values'] = $this->getColumn($sql);
      }
      $field_object->addField($row);
    }
    return $field_object;
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
    if (in_array($row['type'], array('select', 'checkbox-group'))) {
      $sql = $this->sql_builder->selectAllowedValues($row['id']);
      //var_dump($sql);
      $row['values'] = $this->getColumn($sql);
    }
    return new Field($row);
  }
  
  function getFieldsByNames($a_object_id, $a_names) {
    $names = $a_names;
    if (!is_array($names)) {
      $names = array($names);
    }
    
    $field_object = new FieldsObject();
    $field_object->object_id = $a_object_id;
    $sql = $this->sql_builder->selectFieldsByNames($a_object_id, $names);
    
    $dataReader = $this->getReader($sql);
    while(($row = $dataReader->read()) !== false) {
      unset($row['id']);
      $row['id'] = $row['ft_id'];
      unset($row['ft_id']);
      
      if (in_array($row['type'], array('select', 'checkbox-group'))) {
        $sql = $this->sql_builder->selectAllowedValues($row['id']);
        $row['values'] = $this->getColumn($sql);
      }
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
      
      if (in_array($row['type'], array('select', 'checkbox-group'))) {
        $sql = $this->sql_builder->selectAllowedValues($row['id']);
        $row['values'] = $this->getColumn($sql);
      }
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
  
  function deleteAllFor($a_object_id) {
    $sql = $this->sql_builder->deleteAllValues($a_object_id);
    $this->execute($sql);
  }
  
  function deleteEmptyValues($a_object_id) {
    $sql = $this->sql_builder->deleteEmptyValues($a_object_id);
    $this->execute($sql);
  }

}

