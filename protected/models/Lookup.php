<?php
class Lookup extends CActiveRecord
{
    private static $_items=array();
    
    public static function items($type) {
        if(!isset(self::$_items[$type])) {
            self::loadItems($type);
        }
        return self::$_items[$type];
    }
    
    public static function item($type,$code) {
        if(!isset(self::$_items[$type])) {
            self::loadItems($type);
        }
        return isset(self::$_items[$type][$code]) ? self::$_items[$type][$code] : false;
    }
    
    public function loadItems($type) {
        self::$items[$type]=array();
        $models=self::model()->findAll(array(
            'condition'=>'type=:type',
            'params'=>array(':type'=>$type),
            'order'=>'position',
        ));
        foreach($models as $model) {
            self::$_items[$type][$model->code]=$model->name;
        }
    }
    
    public function tableName() {
        return '{{lookup}}';
    }

    public function rules() {
        return array(
                array('name, code, type, position', 'required'),
                array('code, position', 'numerical', 'integerOnly'=>true),
                array('name, type', 'length', 'max'=>128),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, code, type, position', 'safe', 'on'=>'search'),
        );
    }

    public function relations() {
        return array();
    }

    public function attributeLabels() {
        return array(
                'id' => 'ID',
                'name' => 'Name',
                'code' => 'Code',
                'type' => 'Type',
                'position' => 'Position',
        );
    }

    public function search() {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('code',$this->code);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('position',$this->position);

        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
}
