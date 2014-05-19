<?php
/* @var $this PostController */
/* @var $model Post */

$this->breadcrumbs=array(
	'Posts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список тем', 'url'=>array('index')),
	array('label'=>'Изменение тем', 'url'=>array('admin')),
);
?>

<h1>Создание темы</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>