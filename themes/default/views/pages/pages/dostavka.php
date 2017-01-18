<div>
    <!-- breadcrumbs (begin) -->
    <ul class="breadcrumbs">
        <li><a href="/"><?=Yii::t('main','Home page')?></a></li>
        <li>&nbsp;/&nbsp;</li>
        <li><?=Yii::t('main','Shipping and payment')?></li>
    </ul>
    <!-- breadcrumbs (end) -->


    <h1 class="page-title"><?=$model->title?></h1>

    <div class="g-clearfix">
        <div class="content-img">
            <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/delivery.jpg" alt=""/>
        </div>
        <div class="content-text delivery-content-text">
            <?php echo $model->full_description; ?>
        </div>
        <div class="content-text payment-content-text">
            <h2><?=Yii::t('main','Payment')?></h2>
            <p><strong><?=Yii::t('main','Payment methods')?>:</strong></p>
            <ol>
                <li>
                    <?=Yii::t('main','Cash from hand to hand - you can transfer money to our representative in your city. To do this, select the payment method when placing an order, leave your phone
                     and we will contact you by phone or e-mail to arrange a transfer of money.')?>
                </li>
                <li>
                    <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/payment-visa.jpg" width="68" height="34" style="margin-right: 15px;" alt=""/>
                    <strong>Visa</strong>
                </li>
                <li>
                    <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/payment-paypal.jpg" width="68" height="34" style="margin-right: 15px;" alt=""/>
                    <strong>Paypal</strong>
                </li>
                <!--<li>
                    <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/payment-webmoney.jpg" width="68" height="34" style="margin-right: 15px;" alt=""/>
                    <strong>Webmoney</strong>
                </li>
                <li>
                    <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/payment-yamoney.jpg" width="68" height="34" style="margin-right: 15px;" alt=""/>
                    <strong>Яндекс деньги</strong>
                </li>
                <li>
                    <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/payment-qiwi.jpg" width="68" height="34" style="margin-right: 15px;" alt=""/>
                    <strong> Qiwi</strong>
                </li>-->
                <li>
                    <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/payment-card.jpg" width="68" height="34" style="margin-right: 15px;" alt=""/>
                    <strong><?=Yii::t('main','Bank Transfer')?></strong>
                </li>
            </ol>
        </div>
    </div>
</div>
