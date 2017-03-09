<?php

return array(
	'id'=>'DeliveryRegionUpdateForm',
	'showErrorSummary'=>true,
	'enctype'=>'multipart/form-data',
	'elements'=>array(
		'content'=>array(
			'type'=>'form',
			'title'=>Yii::t('StoreModule.admin', 'Общая информация'),
			'elements'=>array(
				'name'=>array(
					'type'=>'text',
				),
                'region_id' => array(
                    'type' => 'dropdownlist',
                    'items' => Region::model()->language(1)->getRegionList(true),
                ),
				'delivery'=>array(
					'type'=>'text',
				),
				'show_in_popup'=>array(
					'type'=>'checkbox',
				),
                'h1_header'=>array(
                    'type'=>'text',
                ),
                'firm_name'=>array(
                    'type'=>'text',
                ),
                'firm_address'=>array(
                    'type'=>'text',
                ),
                'firm_phone'=>array(
                    'type'=>'text',
                ),
                'firm_show'=>array(
                    'type'=>'checkbox',
                ),
			),
		),
		
		
	),
);

