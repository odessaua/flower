<p><?php echo Yii::t('main','In your cart');?> <b><?php echo Yii::t('main','items');?>: <?php echo Yii::app()->cart->countItems() ?></b></p>
<p><?php echo Yii::t('main','for the sum of');?> <b class="price">
	<?php echo StoreProduct::formatPrice(Yii::app()->currency->convert(Yii::app()->cart->getTotalPrice())) ?> <?php echo Yii::app()->currency->active->symbol ?>
</b></p>




