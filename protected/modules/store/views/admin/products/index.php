<?php

/**
 * Display products list
 * @var StoreProduct $model
 **/

$this->pageHeader = Yii::t('StoreModule.admin', 'Продукты');

$this->sidebarContent = $this->renderPartial('_sidebar', array(), true);

// Register scripts
Yii::app()->clientScript->registerScriptFile(
	$this->module->assetsUrl.'/admin/products.index.js',
	CClientScript::POS_END
);

$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin'),
	Yii::t('StoreModule.admin', 'Продукты'),
);

$this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
	'template'=>array('create', 'showSidebar'),
	'elements'=>array(
		'create'=>array(
			'link'=>$this->createUrl('create'),
			'title'=>Yii::t('StoreModule.admin', 'Создать продукт'),
			'options'=>array(
				'icons'=>array('primary'=>'ui-icon-plus')
			)
		),
		'showSidebar'=>array(
			'link'=>'#',
			'title'=>Yii::t('StoreModule.admin', 'Переключить сайдбар'),
			'onclick'=>'js:function(){productToggleSidebar()}',
			'options'=>array(
				'text'=>false,
				'icons'=>array('primary'=>'ui-icon-triangle-2-e-w')
			)
		),
	),
));
$csrfTokenName = Yii::app()->request->csrfTokenName;
                        $csrfToken = Yii::app()->request->csrfToken;
                        $csrf = "\r\ndata:{ '$csrfTokenName':'$csrfToken' },";
$this->widget('ext.sgridview.SGridView', array(
	'dataProvider'  => $dataProvider,
	'id'            => 'productsListGrid',
	'ajaxUpdate'    => true,
	'filter'        => $model,
	'customActions' => array(
		array(
			'label'=>Yii::t('StoreModule.admin', 'Активен'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return setProductsStatus(1, this);',
			),
		),
		array(
			'label'=>Yii::t('StoreModule.admin', 'Не активен'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return setProductsStatus(0, this);',
			),
		),
		array(
			'label'=>Yii::t('StoreModule.admin', 'Назначить категории'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return showCategoryAssignWindow(this);',
			),
		),
		array(
			'label'=>Yii::t('StoreModule.admin', 'Копировать'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return showDuplicateProductsWindow(this);',
			),
		),
        array(
            'label'=>Yii::t('StoreModule.admin', 'Добавить в Витрину'),
            'url'=>'#',
            'linkOptions'=>array(
                'onClick'=>'return setProductsMainPage(1, this);',
            ),
        ),
        array(
            'label'=>Yii::t('StoreModule.admin', 'Удалить из Витрины'),
            'url'=>'#',
            'linkOptions'=>array(
                'onClick'=>'return setProductsMainPage(0, this);',
            ),
        ),
	 ),
	'columns'=>array(
		array(
			'class'=>'CCheckBoxColumn',
		),
		array(
			'class'=>'SGridIdColumn',
			'name'=>'id'
		),
		array(
			'name'=>'name',
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->name), array("/store/admin/products/update", "id"=>$data->id))',
		),
//		array(
//			'name'=>'url',
//			'type'=>'raw',
//			'value'=>'CHtml::link(CHtml::encode($data->url), array("/store/frontProduct/view", "url"=>$data->url), array("target"=>"_blank"))',
//		),
		'price',
		array(
			'header'   => 'Изображение',
			'type'   => 'html',
			'value'=>'($data->mainImage) ? CHtml::link(CHtml::image($data->mainImage->getUrl("50x50","resize")),array("/store/admin/products/update", "id"=>$data->id)) : "no image"',
		),
		array(
			'name'=>'type_id',
			'type'=>'raw',
			'value'=>'CHtml::encode($data->type->name)',
			'filter'=>CHtml::listData(StoreProductType::model()->findAll(), "id", "name"),
		),
		array(
			'name'=>'is_active',
			'cssClassExpression' =>'status',
			'filter'=>array(1=>Yii::t('StoreModule.admin', 'Да'), 0=>Yii::t('StoreModule.admin', 'Нет')),
			'value'=>'$data->is_active ? Yii::t("StoreModule.admin", "Да") : Yii::t("StoreModule.admin", "Нет")'
		),
        array(
            'name'=>'main_page',
            'cssClassExpression' =>'status1',
            'filter'=>array(1=>Yii::t('StoreModule.admin', 'Да'), 0=>Yii::t('StoreModule.admin', 'Нет')),
            'value'=>'$data->main_page ? Yii::t("StoreModule.admin", "Да") : Yii::t("StoreModule.admin", "Нет")'
        ),
		// Buttons
		array(
			'class'=>'CButtonColumn',
			'template'=>"{update}{delete}",
			// 'buttons' => array(
			// 				// "Yii::t('StoreModule.admin', 'Активен')"=>array(
			// 				// 	'label'=>Yii::t('StoreModule.admin', 'Активен'),
			// 				// 	'url'=>'#',
			// 				// 	// 'linkOptions'=>array(
			// 				// 		'click'=>'return  setProductsStatus(1, this);',
			// 				// 	// ),
			// 				// ),
			// 				"Деактевировать"=>array(
			// 					'label'=>'Деактевировать',
			// 					'url'=>'#',

			// 					// 'linkOptions'=>array(
			// 						'click'=>"js:function(){alert('$data->name');}",
			// 					// ),
			// 				),
			// 					// "Yii::t('StoreModule.admin', 'Назначить категории')"=>array(
			// 					// 	'label'=>Yii::t('StoreModule.admin', 'Назначить категории'),
			// 					// 	'url'=>'#',
			// 					// 	'linkOptions'=>array(
			// 					// 		'onClick'=>'return showCategoryAssignWindow(this);',
			// 					// 	),
			// 					// ),
			// 					// "Yii::t('StoreModule.admin', 'Копировать')"=>array(
			// 					// 	'label'=>Yii::t('StoreModule.admin', 'Копировать'),
			// 					// 	'url'=>'#',
			// 					// 	'linkOptions'=>array(
			// 					// 		'onClick'=>'return showDuplicateProductsWindow(this);',
			// 					// 	),
			// 					// 					)
			// 			 ),
		),

	),
));