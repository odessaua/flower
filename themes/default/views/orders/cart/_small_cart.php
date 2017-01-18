<i><b></b></i>
 <?php  echo Yii::app()->cart->countItems().Yii::t('OrderModule.core','items') ?>, <span><?php echo StoreProduct::formatPrice(Yii::app()->currency->convert(Yii::app()->cart->getTotalPrice())) ?></span> <?php echo Yii::app()->currency->active->symbol ?>
