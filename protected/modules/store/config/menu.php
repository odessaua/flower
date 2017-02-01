<?php

Yii::import('application.modules.store.StoreModule');

/**
 * Admin menu items for store module
 */
return array(
	'catalog'=>array(
		'position'=>3,
		'items'=>array(
			array(
				'label'=>Yii::t('StoreModule.admin', 'Все товары'),
				'url'=>Yii::app()->createUrl('store/admin/products'),
				'position'=>1
			),
				array(
				'label'=>Yii::t('StoreModule.admin', 'Типы товаров'),
				'url'=>Yii::app()->createUrl('store/admin/productType'),
				'position'=>2
			),
			array(
				'label'=>Yii::t('StoreModule.admin', 'Категории'),
				'url'=>Yii::app()->createUrl('store/admin/category/create'),
				'position'=>3
			),
			
			array(
				'label'=>Yii::t('StoreModule.admin', 'Варианты товара'),
				'url'=>Yii::app()->createUrl('store/admin/attribute'),
				'position'=>4
			),
			array(
				'label'=>Yii::t('StoreModule.admin', 'Регионы доставки'),
				'url'=>Yii::app()->createUrl('store/admin/deliveryRegions'),
				'position'=>5
			),

            array(
                'label'=>Yii::t('StoreModule.admin', 'Области Украины'),
                'url'=>Yii::app()->createUrl('store/admin/region'),
                'position'=>6
            ),

            array(
                'label'=>Yii::t('StoreModule.admin', 'SEO для городов'),
                'url'=>Yii::app()->createUrl('store/admin/citySeo'),
                'position'=>7
            ),
		
			array(
				'label'=>Yii::t('StoreModule.admin', 'Стоимость доставки'),
				'url'=>Yii::app()->createUrl('store/admin/delivery'),
				'position'=>8
			),

			array(
				'label'=>Yii::t('StoreModule.admin', 'Варианты оплаты'),
				'url'=>Yii::app()->createUrl('store/admin/paymentMethod'),
				'position'=>9
			),
			array(
				'label'=>Yii::t('StoreModule.admin', 'Курсы валют'),
				'url'=>Yii::app()->createUrl('store/admin/currency'),
				'position'=>10
			),
			array(
				'label'=>Yii::t('StoreModule.admin', 'Производители'),
				'url'=>Yii::app()->createUrl('store/admin/manufacturer'),
				'position'=>11
			),
		),
	),
);