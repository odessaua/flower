<i><b></b></i>
товаров: <?php echo Yii::app()->cart->countItems() ?>, <span><?php echo StoreProduct::formatPrice(Yii::app()->currency->convert(Yii::app()->cart->getTotalPrice())) ?></span> <?php echo Yii::app()->currency->active->symbol ?>
