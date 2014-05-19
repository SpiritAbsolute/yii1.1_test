<?php
class Tag extends CActiveRecord
{
    public function tableName() {
        return '{{tag}}';
    }

    public function rules() {
        return array(
                array('name', 'required'),
                array('frequency', 'numerical', 'integerOnly'=>true),
                array('name', 'length', 'max'=>128),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, frequency', 'safe', 'on'=>'search'),
        );
    }

    public function relations() {
        return array();
    }

    public function attributeLabels() {
        return array(
                'id' => 'ID',
                'name' => 'Name',
                'frequency' => 'Frequency',
        );
    }

    public function search() {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('frequency',$this->frequency);

        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public static function string2array($tags) {
        return preg_split('/\s*,\s*/',trim($tags),-1,PREG_SPLIT_NO_EMPTY);
    }

    public static function array2string($tags) {
        return implode(', ',$tags);
    }
}
