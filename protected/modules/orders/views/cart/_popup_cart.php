<p><?php echo Yii::t('main','Shopping cart');?> <b><?php echo Yii::t('main','items');?>: <?php echo Yii::app()->cart->countItems() ?></b></p>
<p><?php echo Yii::t('main','for the amount');?> <b class="price"><?php echo Yii::app()->currency->active->symbol ?>
	<?php echo StoreProduct::formatPrice(Yii::app()->currency->convert(Yii::app()->cart->getTotalPrice())) ?> 
</b></p>




