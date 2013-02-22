<?php

  require '../../models/Field.php';
  require '../../utils/SqlBuilder.php';
  
  $field = array(
    'group_id' => 'group1',
    'weight'   => 1,
    'type'     => 'select',
    'name'     => 'marks',
    'label'    => 'Марка',
    'values'   => array('Audi', 'BMW')
  );
  
  $builder = new SqlBuilder();
  
  echo $builder->insertField($field) . PHP_EOL;
  echo $builder->getFieldId($field) . PHP_EOL;
  echo $builder->insertAllowedValue(1, 'Audi') . PHP_EOL;
  echo $builder->selectFieldsFor(NULL, array("step1")) . PHP_EOL;