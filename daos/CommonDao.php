<?php

class CommonDao {
  
  function execute($sql) {
    Yii::app()->db->createCommand($sql)->execute();
  }
  
  function getScalar($sql) {
    return Yii::app()->db->createCommand($sql)->queryScalar();
  }
  
  function getReader($sql) {
    return Yii::app()->db->createCommand($sql)->query();
  }
  
  function getFirst($sql) {
    return Yii::app()->db->createCommand($sql)->queryRow();
  }
  
  function getColumn($sql) {
    return Yii::app()->db->createCommand($sql)->queryColumn();
  }
  
  function getAll($sql) {
    return Yii::app()->db->createCommand($sql)->queryAll();
  }
  
}
