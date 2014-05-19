<?php
class Post extends CActiveRecord
{
    const STATUS_DRAFT=1;
    const STATUS_PUBLISHED=2;
    const STATUS_ARCHIVED=3;

    public function tableName() {
        return '{{post}}';
    }

    public function rules() {
        return array(
            array('title, content, status', 'required'),
            array('title', 'length', 'max'=>128),
            array('status', 'in', 'range'=>array(1,2,3)),
            array('tags', 'match', 'pattern'=>'/^[\w\s,]+$/', 
                'message'=>'В тегах можно использовать только буквы.'),
            array('tags', 'normalizeTags'),
            array('title, status', 'safe', 'on'=>'search'),
        );
    }

    public function relations() {
        return array(
            'author' => array(self::BELONGS_TO, 'User', 'author_id'),
            'comments' => array(self::HAS_MANY, 'Comment', 'post_id',
                'condition'=>'comments.status='.Comment::STATUS_APPROVED,
                'order'=>'comments.create_time DESC'),
            'commentCount'=>array(self::STAT, 'Comment', 'post_id',
                'condition'=>'status='.Comment::STATUS_APPROVED),

        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => 'Заголовок',
            'content' => 'Сообщение',
            'tags' => 'Метки',
            'status' => 'Статус',
            'create_time' => 'Дата создания',
            'update_time' => 'Дата обновления',
            'author_id' => 'Автор',
        );
    }

    public function search() {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('content',$this->content,true);
        $criteria->compare('tags',$this->tags,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('create_time',$this->create_time);
        $criteria->compare('update_time',$this->update_time);
        $criteria->compare('author_id',$this->author_id);

        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function normalizeTags($attribute, $params) {
        $this->tags=Tag::array2string(array_unique(Tag::string2array($this->tags)));
    }

    public function getUrl() {
        return Yii::app()->createUrl('post/view', array(
            'id'=>$this->id,
            'title'=>$this->title,
        ));
    }
}
