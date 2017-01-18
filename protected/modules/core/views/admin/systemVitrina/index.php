<?php

	/** Display pages list **/
	$this->pageHeader = Yii::t('CoreModule.core', 'Витрина');

	$this->breadcrumbs = array(
		'Home'=>$this->createUrl('/admin'),
		Yii::t('CoreModule.core', 'Витрина'),
	);

	$this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
		'template'=>array('create'),
		'elements'=>array(
			'create'=>array(
				'link'=>$this->createUrl('create'),
				'title'=>Yii::t('CoreModule.core', 'Создать витрину'),
				'icon'=>'plus',
			),
		),
	));

	$this->widget('ext.sgridview.SGridView', array(
		'dataProvider'=>$model->search(),
		'id'=>'languagesListGrid',
		'filter'=>$model,
		//'ajaxUpdate'=>false,
		'columns'=>array(
			array(
				'class'=>'CCheckBoxColumn',
			),
			array(
				'class'=>'SGridIdColumn',
				'name'=>'id',
			),
			array(
				'name'=>'name',
				'type'=>'raw',
				'value'=>'CHtml::link(CHtml::encode($data->name), array("update", "id"=>$data->id))',
			),

			array(
				'name'=>'is_active',
				'filter'=>CHtml::listData(SSystemVitrina::model()->findAll('is_active', 1)),
				'value'=>'is_active',		
				
			),
			array(
				'name'=>'num_products',
				'filter'=>SSystemVitrina::model()->findAll('num_products', ''),
				'type'=>'raw',
				'value'=>'num_products',
			),

		),
	));
