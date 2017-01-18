<div>
<!-- breadcrumbs (begin) -->
    <ul class="breadcrumbs">
        <li><a href="/" title=""><?=Yii::t('OrdersModule.core','Home')?></a></li>
        <li>&nbsp;/&nbsp;</li>
        <li><?=Yii::t('OrdersModule.core','Cart')?></li>
    </ul>
    <!-- breadcrumbs (end) -->

    <!-- steps (begin) -->
    <div class="steps">
        <div class="step1 ">
            <b>1</b>
            <p><?php echo Yii::t('OrdersModule.core','Your order')?></p>
        </div>
        <div class="step2 ">
            <b>2</b>
            <p><?php echo Yii::t('OrdersModule.core','Checkout')?></p>
        </div>
        <div class="step3 active">
            <b>3</b>
            <p><?php echo Yii::t('OrdersModule.core','Payment')?></p>
        </div>
        <div class="step4">
            <b>4</b>
            <p><?php echo Yii::t('OrdersModule.core','Done')?></p>
        </div>
    </div>
    <!-- steps (end) -->

    <h1 class="page-title"><?php echo Yii::t('OrdersModule.core','Your order')?></h1>

    <div class="cart3 g-clearfix">
       
        <div class="data-form data-form-big">
            <table><tr><td>
            <b class="title"><?=yii::t('OrdersModule.core','Select a Payment Method:')?></b>
            <ul class="payment-list">
                <li>
                    <input type="radio" name="payment" id="payment1"/>
                    <label for="payment1">
                        <img src="/uploads/payment-visa.jpg" alt=""/>
                        <span>Visa</span>
                    </label>
                </li>
                <li>
                    <input type="radio" name="payment" id="payment2"/>
                    <label for="payment2">
                        <img src="/uploads/payment-paypal.jpg" alt=""/>
                        <span>Paypal</span>
                    </label>
                </li>
                <li>
                    <input type="radio" name="payment" id="payment3"/>
                    <label for="payment3">
                        <img src="/uploads/payment-card.jpg" alt=""/>
                        <span><?=yii::t('OrdersModule.core','In cash')?></span>
                    </label>
                </li>
                
            </ul>
            <div class="links">
                <a class="link-next" href="#" title=""><?=yii::t('OrdersModule.core','Pay')?></a>
            </div>
        </td></tr>
		<tr><td><br><br>
            <div class="sub-title"><?=Yii::t('OrdersModule.core','The recipient will be awarded:')?></div>
        <ul class="cart-products">
            <?php foreach($model->getOrderedProducts()->getData() as $product): ?>
                <li>
                    <div class="visual">
                        <?php
                        $pro_model = StoreProduct::model()->findByPk($product->product_id);
                        ?>
                        <a href="/product/<?=$pro_model->url?>.html" title="">
                            <img src="<?=$pro_model->mainImage->getUrl('85x85', 'resize')?>"/>
                        </a>
                    </div>
                    <div class="text">
                        <div class="name"><?php echo $product->getRenderFullName(false); ?></div>
                    </div>
                </li>
            <?php endforeach ?>
            <?php
                echo Yii::t('OrdersModule.core','Delivery price ').$model->delivery_price*$rate."UAH<br>";
            ?>

            <?php if(!empty($model->do_card))echo Yii::t('OrdersModule.core','Card price')." - ".$cardPrice*$rate."UAH<br>"?>
            <?php if(!empty($model->doPhoto))echo Yii::t('OrdersModule.core','Photo price')." - ".$photoPrice*$rate."UAH<br>"?>
            <?php $total=$model->full_price*$rate; echo $total;?>
			<div class="sum">

            <?php
               echo Yii::t('OrdersModule.core','Order sum ').$total."UAH";
            ?>

    </div>
        </ul>
        </td>
      
                        </tr></table>
                    </div>
                    <!-- data-form (end) -->
                </form>
            </div>
<div class="cart4">
    <h2 class="title"><?=Yii::t('OrdersModule.core','Congratulations! Your order is accepted for processing.')?></h2>

    <div class="g-clearfix">
        <div class="cart-col">
            <div class="sub-title"><?=Yii::t('OrdersModule.core','Order details')?></div>
            <ul class="cart-details">
                <li>
                    <p><?=Yii::t('OrdersModule.core','Receiver name')?>: <b><?=$model->receiver_name ?></b></p>
                </li>
                <li>
                    <p><?=Yii::t('OrdersModule.core','Country:')?><b><?=$model->country?></b></p>
                </li>
                <li>
                    <p><?=Yii::t('OrdersModule.core','City:')?> <b><?=$model->city ?></b></p>
                </li>
                <li>
                    <p><?=Yii::t('OrdersModule.core','Receiver adress')?> <b><?=$model->user_address ?></b></p>
                </li>
                <li>
                    <p><?=Yii::t('OrdersModule.core','Phone1')?> <b><?=$model->phone1 ?></b></p>
                    <p><?=Yii::t('OrdersModule.core','Phone2')?> <b><?=$model->phone2?></b></p>
                </li>
                <li>
                    <p><?=Yii::t('OrdersModule.core','Date and time of delivery:')?> <b><?=$model->datetime_del?></b></p>
                </li>
                <li>
                    <p><?=Yii::t('OrdersModule.core','Additional Information:')?><b><?=$model->user_comment ?></b></p>
                </li>
                <li>
                    <p><?=Yii::t('OrdersModule.core','Make a photo of the recipient')?> <b><?php if($model->doPhoto) echo "Da"; else echo "Net"; ?></b></p>
                </li>
                <li>
                    <p><?=Yii::t('OrdersModule.core','Send greeting card')?> <b><?php if($model->do_card) echo "Da"; else echo "Net"; ?></b></p>
                </li>
                <li>
                    <p><?=Yii::t('OrdersModule.core','Text of postcard:')?><b><?=$model->card_text?></b></p>
                </li>
            </ul>
        </div>
        <div class="cart-col">
            <div class="sub-title"><?=Yii::t('OrdersModule.core','The recipient will be awarded:')?></div>
        <ul class="cart-products">
            <?php foreach($model->getOrderedProducts()->getData() as $product): ?>
                <li>
                    <div class="visual">
                        <?php
                        $pro_model = StoreProduct::model()->findByPk($product->product_id);
                        ?>
                        <a href="/product/<?=$pro_model->url?>.html" title="">
                            <img src="<?=$pro_model->mainImage->getUrl('85x85', 'resize')?>"/>
                        </a>
                    </div>
                    <div class="text">
                        <div class="name"><?php echo $product->getRenderFullName(false); ?></div>
                    </div>
                </li>
            <?php endforeach ?>
        </ul>
            <div class="thanks">
                <?=Yii::t('OrdersModule.core','We will do everything to ensure that the recipient liked
         Your gift and you satisfied with our service!
         Thank you for your trust!')?>
            </div>
        </div>
    </div>
</div> 




                    
                




                  

</div>
 <form class="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
    <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
    <br>
    <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
    <br>
</form> 
<form class="portmone" action="https://www.portmone.com.ua/gateway/" method="post" name="paymentform">
    <input type="hidden" name="payee_id" value="2046">
    <input type="hidden" name="shop_order_number" value="<?=$model->id?>">
    <input type="hidden" name="bill_amount" value="<?=$total?>">
    <input type="hidden" name="description" value="ATTENTION! Amount above is given in Ukraine currency calculated automatically according to the current rate of the Ukraine National Bank">
    <input type="hidden" name="success_url" value="http://www.7roses.com.ua/payment.php">
    <input type="hidden" name="failure_url" value="">
    <INPUT TYPE="hidden" NAME="lang" VALUE="ru">
    <image width="282" height="100" src='/uploads/image.jpg' />
    <input type="submit" class="btn-purple" value="<?=Yii::t('main','Pay')?> ">
</form>
  

<script type="text/javascript">
$(document).ready(function(){
$('.portmone').css('display','none');
$('.cart4').css('display','none');
$('.paypal').css('display','none');
$('.payment-list li').click(function() {
    $('.payment-list li').removeClass('selected'); // removes the "selected" class from all tabs
    $(this).addClass('selected');
});
$(".radio_payment").click(function(){
var payment_id = $(this).val();	
var order_id = <?=$model->id?>;
$(".link-next").show();

$.ajax({
type: "GET",
url: "/site/setPaymentId",
data: {payment_id : payment_id, order_id : order_id}
});
});

$(".link-next").click(function(){
if($($('.selected').children()[0]).attr('id')=="payment1")
        $('.portmone').submit();
else if($($('.selected').children()[0]).attr('id')=="payment2")
        $('.paypal').submit();
else if($($('.selected').children()[0]).attr('id')=="payment3"){
    $('.cart4').css('display','block');
    $('.cart3').css('display','none');
    $('.step4').addClass('active');
    $('.step3').removeClass('active');

}
})
});
</script>
