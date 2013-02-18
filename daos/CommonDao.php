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
  
}