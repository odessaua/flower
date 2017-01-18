<?php

/**
 * Create/update language form 
 */

return array(
	'id'=>'languageUpdateForm',
    'enctype'=>'multipart/form-data',
	'elements'=>array(
		'name'=>array(
            'type'=>'text',
        ),
		'num_products'=>array(
            'type'=>'text',
            ),
    'id_products'=>array(
            'type'=>'text',),
	'is_active'=>array(
					'type'=>'dropdownlist',
					'items'=>array(
						1=>Yii::t('StoreModule.admin', 'Да'),
						0=>Yii::t('StoreModule.admin', 'Нет')
					),
        ),
		// 'locale'=>array(
  //           'type'=>'text',
  //           'hint'=>Yii::t('CoreModule.core', 'Например: en, en_us'),
  //       ),
  //       'flag_name'=>array(
  //           'type'=>'dropdownlist',
  //           'items'=>SSystemLanguage::getFlagImagesList(),
  //           'empty'=>'---',
  //           //'encode'=>false,
  //       ),        
		// 'default'=>array(
  //           'type'=>'checkbox',
  //       )
	),
);

