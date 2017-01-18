<?php

/**
 * @var StoreProduct $data
 */
?>

<div class="b-product">
    <div class="visual">
    	<?php
		if($data->mainImage)
			$imgSource = $data->mainImage->getUrl('135x19950');
		else
			$imgSource = 'http://placehold.it/13590x19950';
		echo CHtml::link(CHtml::image($imgSource, $data->mainImageTitle), array('frontProduct/view', 'url'=>$data->url), array('rel'=>'nofollow'));
		?>
    </div>
    <div class="title">
        <?php  
        $lang= Yii::app()->language;
                    if($lang == 'ua')
                        $lang = 'uk';
        $langArray = SSystemLanguage::model()->findByAttributes(array('code'=>$lang));
        $trans=Yii::app()->db->createCommand()
		    ->select('name,')
		    ->from('StoreProductTranslate')
		    ->where('language_id=:lang_id',array('lang_id'=>$langArray->id))
		    ->andWhere('object_id=:prod_id',array('prod_id'=>$data->id))
		    ->queryRow();
        		echo CHtml::link(CHtml::encode($trans['name']), array('frontProduct/view', 'url'=>$data->url)) ?>
    </div>
    <span class="price"><?php echo $data->priceRange() ?></span>
    <div class="form">
    	<?php
			echo CHtml::form(array('/orders/cart/add'));
			echo CHtml::hiddenField('product_id', $data->id);
			echo CHtml::hiddenField('product_price', $data->price);
			echo CHtml::hiddenField('use_configurations', $data->use_configurations);
			echo CHtml::hiddenField('currency_rate', Yii::app()->currency->active->rate);
			echo CHtml::hiddenField('configurable_id', 0);
			echo CHtml::hiddenField('quantity', 1);

			if($data->getIsAvailable())
			{
				echo CHtml::ajaxSubmitButton(Yii::t('StoreModule.core','Order'), array('/orders/cart/add'), array(
					'id'=>'addProduct'.$data->id,
					'dataType'=>'json',
					'success'=>'js:function(data, textStatus, jqXHR){processCartResponseFromList(data, textStatus, jqXHR, "'.Yii::app()->createAbsoluteUrl('/store/frontProduct/view', array('url'=>$data->url)).'")}',
				), array('class'=>'btn-purple'));
			}
			else
			{
				echo CHtml::link('Нет в наличии', '#', array(
					'onclick' => 'showNotifierPopup('.$data->id.'); return false;',
					'class'   => 'notify_link',
				));
			}
		?>
		<?php echo CHtml::endForm() ?>
    </div>
</div>