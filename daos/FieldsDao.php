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
  function saveFields($fields) {
    foreach ($fields as $field) {
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
    }
  }
  
  function getFieldsFor($a_object_id, $a_group_ids) {
    $object_id = $a_object_id;
    $group_ids = $a_group_ids;
    if (!is_array($group_ids)) {
      $group_ids = array($group_ids);
    }
    
    $field_object = new FieldsObject();
    $sql = $this->sql_builder->selectFieldsFor($object_id, $group_ids);
    $dataReader = $this->getReader($sql);
    while(($row = $dataReader->read()) !== false) {
      $field_object->addField($row);
    }
    return $field_object;
  }
  
  function saveFieldsValuesFor($a_object_id, $a_fields_values) {
  }

}

