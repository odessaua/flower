<?php

/**
 * @var StoreProduct $data
 */

// product Main category
$mc_sql = "SELECT sc.url
FROM StoreCategory sc
LEFT JOIN StoreProductCategoryRef spcr ON spcr.category = sc.id
WHERE spcr.product = " . $data->id . "
AND spcr.is_main = 1
LIMIT 1";
$mainCat = Yii::app()->db->createCommand($mc_sql)->queryRow();
// product url
$product_url = (!empty($mainCat['url']))
    ? '/' . $mainCat['url'] . '/' . $data->url . '.html'
    : array('frontProduct/view', 'url'=>$data->url);
// product name
$trans=Yii::app()->db->createCommand()
    ->select('name,')
    ->from('StoreProductTranslate')
    ->where('language_id=:lang_id',array('lang_id'=>$langArray->id))
    ->andWhere('object_id=:prod_id',array('prod_id'=>$data->id))
    ->queryRow();
// images alt & title
$img_alt = (!empty($data->img_alt)) ? $data->img_alt : $trans['name'];
$img_title = (!empty($data->img_title)) ? $data->img_title : $trans['name'];

?>

<div class="b-product">
    <div class="visual">
    	<?php
		if($data->mainImage) {
            $imgSource = $data->mainImage->getUrl('135x199');
            if(!file_exists('./' . $imgSource)) $imgSource = 'http://placehold.it/135x199/ffffff?text=7Roses';
        }
		else
			$imgSource = 'http://placehold.it/135x199/ffffff?text=7Roses';
		echo CHtml::link(CHtml::image($imgSource, $img_alt, array('title' => $img_title)), $product_url, array('rel'=>'nofollow'));
		?>
    </div>
    
   <div class="title">
        <?php echo CHtml::link(CHtml::encode($trans['name']), $product_url) ?>
    </div>
    
    <span class="price">
    	<?php echo $data->priceRange() ?>
    </span>
    
    <div class="form">
    	<?php /*
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
			echo CHtml::link(Yii::t('main','Not available'), '#', array(
				'onclick' => 'showNotifierPopup('.$data->id.'); return false;',
				'class'   => 'notify_link',
			));
		}
		?>
		<?php echo CHtml::endForm()*/ ?>

        <?php
        echo CHtml::button(Yii::t('StoreModule.core','Order'), array(
            'class'   => 'btn-purple',
            'submit' => $product_url,
        ));
        ?>

    </div>
</div>