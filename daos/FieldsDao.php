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
    
    return $this->getField(null, $field[Field::NAME]);
  }
  
  function getField($a_object_id, $a_field_name) {    
    $sql = $this->sql_builder->selectField($a_object_id, $a_field_name);    
    $row = $this->getFirst($sql);
    if ($row[Field::TYPE] == Field::SELECT_TYPE) {
      $sql = $this->sql_builder->selectAllowedValues($row['id']);
      $row[Field::VALUES] = $this->getColumn($sql);
    }
    return new Field($row);
  }
  
  function getFields($a_object_id, $a_group_ids) {
    $group_ids = $a_group_ids;
    if (!is_array($group_ids)) {
      $group_ids = array($group_ids);
    }
    
    $field_object = new FieldsObject();
    $sql = $this->sql_builder->selectFields($a_object_id, $group_ids);
    $dataReader = $this->getReader($sql);
    while(($row = $dataReader->read()) !== false) {
      $field_object->addField($row);
    }
    return $field_object;
  }

}

