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
// images alt & title
$img_alt = (!empty($data->img_alt)) ? $data->img_alt : $trans['name'];
$img_title = (!empty($data->img_title)) ? $data->img_title : $trans['name'];

?>

<div class="b-product">
    <div class="visual">
    	<?php
		if($data->mainImage)
			$imgSource = $data->mainImage->getUrl('135x19950');
		else
			$imgSource = 'http://placehold.it/13590x19950';
		echo CHtml::link(CHtml::image($imgSource, $img_alt, array('title' => $img_title)), $product_url, array('rel'=>'nofollow'));
		?>
    </div>
    <div class="title">
        <?php echo CHtml::link(CHtml::encode($trans['name']), $product_url) ?>
    </div>
    <span class="price"><?php echo $data->priceRange() ?></span>
    <div class="form">
        <?php
        echo CHtml::button(Yii::t('StoreModule.core','Order'), array(
            'class'   => 'btn-purple',
            'submit' => $product_url,
        ));
        ?>
    </div>
</div>