<?php

class FieldsDao extends CommonDao {

  private $sql_builder;

  function __constructor() {
    $sql_builder = new SqlBuilder();
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
      $sql = $sql_builder->insertField($field);
      $this->execute($sql);
      
      $sql = $sql_builder->getFieldId($field);
      $field_type_id = $this->getScalar($sql);
      
      if (is_set($field[Field::VALUE])) {
        foreach ($field[Field::VALUE] as $value) {
          $sql = $sql_builder->insertAllowedValue($field_type_id, $value);
          $this->execute($sql);
        }
      }
    }
  }
  
  function getFieldsFor($a_object_id, $a_category_ids) {
    $object_id = $a_object_id;
    $category_ids = $a_category_ids;
    if (!is_array($category_ids)) {
      $category_ids = array($category_ids);
    }
    
    //$dataReader = $this->getReader($sql);
    //while(($row = $dataReader->read()) !== false) {
    //}
  }
  
  function saveFieldsValuesFor($a_object_id, $a_fields_values) {
  }

}

