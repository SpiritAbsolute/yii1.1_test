<?php
class Comment extends CActiveRecord
{
    const STATUS_PENDING=1;
    const STATUS_APPROVED=2;

    public function tableName() {
        return '{{comment}}';
    }

    public function rules() {
        return array(
                array('content, status, author, email, post_id', 'required'),
                array('status, create_time, post_id', 'numerical', 'integerOnly'=>true),
                array('author, email, url', 'length', 'max'=>128),
                array('id, content, status, create_time, author, email, url, post_id', 'safe', 'on'=>'search'),
        );
    }

    public function relations() {
        return array(
                'post' => array(self::BELONGS_TO, 'Post', 'post_id'),
        );
    }

    public function attributeLabels() {
        return array(
                'id' => 'ID',
                'content' => 'Сообщение',
                'status' => 'Статус',
                'create_time' => 'Дата создания',
                'author' => 'Автор',
                'email' => 'Email',
                'url' => 'Url',
                'post_id' => 'Post',
        );
    }

    public function search() {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('content',$this->content,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('create_time',$this->create_time);
        $criteria->compare('author',$this->author,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('url',$this->url,true);
        $criteria->compare('post_id',$this->post_id);

        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
}
