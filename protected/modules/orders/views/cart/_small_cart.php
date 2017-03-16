<i><b></b></i>
<?php echo Yii::app()->cart->countItems(); echo "  ".Yii::t('main', 'items:')." ";?>
<span><?php echo Yii::app()->currency->active->symbol ?><?php echo StoreProduct::formatPrice(Yii::app()->currency->convert(Yii::app()->cart->getTotalPrice())) ?></span> 
