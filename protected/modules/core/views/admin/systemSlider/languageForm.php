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
    'url'=>array(
            'type'=>'text',
        ),
		'photo'=>array(
            'type'=>'file',
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

