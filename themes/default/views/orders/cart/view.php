<?php
/**
 * Display cart
 * @var CartController $model
 */
?>
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
        <div class="step3 <?= ($model->status_id != 6) ? 'active' : ''; ?>">
            <b>3</b>
            <p><?php echo Yii::t('OrdersModule.core','Payment')?></p>
        </div>
        <div class="step4 <?= ($model->status_id == 6) ? 'active' : ''; ?>">
            <b>4</b>
            <p><?php echo Yii::t('OrdersModule.core','Done')?></p>
        </div>
    </div>
    <!-- steps (end) -->

    <h1 class="page-title"><?php echo Yii::t('OrdersModule.core','Your order')?></h1>
<?php if($model->status_id != 6): ?>
    <div class="cart3 g-clearfix">
       
        <div class="data-form data-form-big">
            <table cellpadding="10px" border=0><tr><td valign=top>
            <b class="title"><?=yii::t('OrdersModule.core','Select a Payment Method:')?></b>
            <ul class="payment-list">
                <li>
                    <input type="radio" name="payment" id="payment1"/>
                    <label for="payment1">
                        <img src="/uploads/payment-visa.jpg" alt="Visa"/>
                        <span>VISA - <? echo StoreProduct::formatPrice($model->full_price*$rate); echo yii::t('OrdersModule.core',' UAH');?></span>
                    </label>
                </li>
                <li>
                    <input type="radio" name="payment" id="payment4"/>
                    <label for="payment4">
                        <img src="/uploads/payment_wfp.png" alt="WeyforPay" style="margin-right: 30px;"/>
                        <span>WayForPay - <? echo StoreProduct::formatPrice($model->full_price*$rate); echo yii::t('OrdersModule.core',' UAH');?></span>
                    </label>
                </li>
                <li>
                    <input type="radio" name="payment" id="payment2"/>
                    <label for="payment2">
                        <img src="/uploads/payment-paypal.jpg" alt="Paypal" title="Paypal"/>
                        <span>PayPal - <?echo StoreProduct::formatPrice($model->full_price); echo yii::t('OrdersModule.core',' USD');?></span>
                    </label>
                </li>
                <li>
                    <input type="radio" name="payment" id="payment3"/>
                    <label for="payment3">
                        <img src="/uploads/payment-card.jpg" alt="Cash"/>
                        <span><?=yii::t('OrdersModule.core','In cash')?></span>
                    </label>
                </li>
                
            </ul>
            <div class="links">
                <a class="link-next" href="#" title=""><?=yii::t('OrdersModule.core','Pay')?></a>
            </div>
        </td><td><td><div></div></td>
		<td>
<?php
$wfp_p_names = $wfp_p_qtys = $wfp_p_prices = array(); // инфа для WayForPay
?>
		<div class="cart-table-result">
		<table  cellpadding=5 border=1>
		<tr>
		<th colspan="3" bgcolor="#eaeae8"><div class="sub-title"><?=Yii::t('OrdersModule.core','Your order set:')?></div></th></tr>
     
            <?php foreach($model->getOrderedProducts()->getData() as $product): ?>
                <?php
                // инфа для WayForPay
                $wfp_p_names[$product->product_id] = str_replace('"', '', $product->name);
                $wfp_p_qtys[$product->product_id] = $product->quantity;
                $wfp_p_prices[$product->product_id] = $product->price*$rate;
                ?>
                <tr><td width="85px">
                    <div class="visual">
                        <?php
                        $pro_model = StoreProduct::model()->findByPk($product->product_id);
						//var_dump ($product);
                        ?>
                        <a href="/product/<?=$pro_model->url?>.html" title="">
                            <img src="<?=$pro_model->mainImage->getUrl('85x85', 'resize')?>"/>
                        </a>
                    </div></td><td>
                    <div class="text">
                        <div class="name"><?php echo $product->getRenderFullName(false); ?></div>
                    </div>
                </td><td width="35%"><span class="price"><?=$product->price?> &#36;</span></td></tr>
            <?php endforeach ?>
			           			
            <?php if(!empty($model->do_card)) { ?>
			<tr><td width="85px"><img src="/uploads/greeting-card.png" alt="Greeting Card" title="Greeting card" width=85 height=85 /></td>
			<td><? echo Yii::t('OrdersModule.core','Card price')?></td>
			<td width="25%"><span class="price"><?=$cardPrice." &#36;</span></td></tr>"; }?>
			
			
            <?php if(!empty($model->doPhoto)){ ?>
			<tr><td width="85px"><img src="/uploads/photo.png" alt="Photo delivery" title="Photo of the delivery" width=85 height=85 /></td>
			<td><? echo Yii::t('OrdersModule.core','Photo of delivery')?></td>
			<td width="25%"><span class="price"><?=$photoPrice." &#36;</span></td></tr>"; }?>	
			
            <tr>
			<td width="85px"><img src="/uploads/delivery.png" alt="Delivery" title="Cost of the delivery" width=80 height=80 /></td>
			<td><?echo Yii::t('OrdersModule.core','Delivery price ');?>	</td>		
			<td width="25%"><span class="price"><?php $delivery=$model->delivery_price; if ($delivery=='0') echo "FREE"; else echo StoreProduct::formatPrice($delivery)." &#36;</span></td></tr>";?>
			
			<tr>
			<td width="85px"><img src="/uploads/sum.png" alt="Total sum" title="Total sum" width=80 height=80 /></td>
			<td><?php echo Yii::t('OrdersModule.core','Total order sum');?></td>
			<td width="25%"><div class="sum"><span class="price"><?echo StoreProduct::formatPrice($model->full_price)." &#36;</span>" ;?></div>(<? echo StoreProduct::formatPrice($model->full_price*$rate)." uah"?>)			

			</td></tr>
			</table>
			</div>
        </td>
        </tr>
    </table>
        </div>
        <!-- data-form (end) -->
    </form>
</div>
<?php elseif($model->status_id == 6): ?>
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
                    <p><?=Yii::t('OrdersModule.core','Make a photo of the recipient')?> <b><?php if($model->doPhoto) echo Yii::t('OrdersModule.core','Yes'); else echo Yii::t('OrdersModule.core','No'); ?></b></p>
                </li>
                <li>
                    <p><?=Yii::t('OrdersModule.core','Send greeting card')?> <b><?php if($model->do_card) echo Yii::t('OrdersModule.core','Yes'); else echo Yii::t('OrdersModule.core','No'); ?></b></p>
                </li>
                <li>
                    <p><?=Yii::t('OrdersModule.core','Text of postcard:')?><b><?=$model->card_text?></b></p>
                </li>
            </ul>
        </div>

        <div class="cart-col">
            <div class="sub-title"><?=Yii::t('OrdersModule.core','The recipient will be awarded')?>:</div>
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
<?php endif; ?>



                    
                




                  

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
    <input type="hidden" name="bill_amount" value="<?=$model->full_price*$rate?>">
    <input type="hidden" name="description" value="ATTENTION! Amount above is given in Ukraine currency calculated automatically according to the current rate of the Ukraine National Bank">
    <input type="hidden" name="success_url" value="http://7roses.com.ua/cart/view/<?=$model->secret_key?>/success/">
    <input type="hidden" name="failure_url" value="http://www.7roses.com.ua/">
    <INPUT TYPE="hidden" NAME="lang" VALUE="<?=Yii::app()->language?>">
    <input type="hidden" name="encoding" value="UTF-8" /> 
    <image width="282" height="100" src='/uploads/image.jpg' />
    <input type="submit" class="btn-purple" value="<?=Yii::t('main','Pay')?> ">
</form>

<?php
// WayForPay merchant info
$merchantDomainName = $_SERVER['HTTP_HOST'];
$wfp_type = 'form'; // form or widget
// merchant signature compilation
//$orderReference = $model->id;
//$orderReference = "ord_" . $model->id; // без префиксов ругается (1112) Duplicate Order ID
$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5); // length = 5
$orderReference = $randomString . "_" . $model->id; // рандомный префикс
$orderDate = strtotime($model->created);
$orderFullPrice = $model->full_price*$rate;
//$orderFullPrice = "1"; // temp
$orderCurrency = "UAH";
$string = Yii::app()->params['merchantAccount'] . ";" . $merchantDomainName . ";" . $orderReference . ";" . $orderDate . ";" . $orderFullPrice . ";" . $orderCurrency;
$string .= (!empty($wfp_p_names)) ? ";" . implode(";", $wfp_p_names) : "";
$string .= (!empty($wfp_p_qtys)) ? ";" . implode(";", $wfp_p_qtys) : "";
$string .= (!empty($wfp_p_prices)) ? ";" . implode(";", $wfp_p_prices) : "";
//var_dump($string);
$merchantSignature = hash_hmac("md5", $string, Yii::app()->params['merchantSecretKey']);
?>

<?php if($wfp_type == 'widget'): ?>
<script id="widget-wfp-script" language="javascript" type="text/javascript" src="https://secure.wayforpay.com/server/pay-widget.js"></script>
<script type="text/javascript">
    var wayforpay = new Wayforpay();
    var wfpay = function () {
        wayforpay.run({
                merchantAccount : "<?=Yii::app()->params['merchantAccount'];?>",
                merchantDomainName : "<?=$merchantDomainName;?>",
                authorizationType : "SimpleSignature",
                merchantSignature : "<?=$merchantSignature;?>",
                orderReference : "<?=$orderReference;?>",
                orderDate : "<?=$orderDate;?>",
                amount : "<?=$orderFullPrice;?>",
                currency : "<?=$orderCurrency;?>",
                productName : [<?='"' . implode('","', $wfp_p_names) . '"';?>],
                productPrice : [<?=implode(',', $wfp_p_prices);?>],
                productCount : [<?=implode(',', $wfp_p_qtys);?>],
                clientFirstName : "<?=$model->user_name;?>",
                clientLastName : "<?='.';?>",
                clientEmail : "<?=$model->user_email;?>",
                clientPhone: "<?=(!empty($model->user_phone)) ? $model->user_phone : '380631234567';?>",
                language: "<?=strtoupper(Yii::app()->language);?>",
                returnUrl: "http://<?=$_SERVER['HTTP_HOST'];?>/cart/view/<?=$model->secret_key?>/success/"
            },
            function (response) {
                // on approved
                document.location.href = "http://<?=$_SERVER['HTTP_HOST'];?>/cart/view/<?=$model->secret_key?>/success/";
                //console.log('Approved: '+response);
            },
            function (response) {
                // on declined
                console.log('Declined: '+response);
            },
            function (response) {
                // on pending or in processing
                console.log('Pending or in Processing: '+response);
            }
        );
    }
</script>
<?php else: ?>
    <form action="https://secure.wayforpay.com/pay" method="post" style="float: left;" class="wayforpay">
        <input type="hidden" name="merchantAccount" value="<?=Yii::app()->params['merchantAccount']; ?>">
        <input type="hidden" name="merchantDomainName" value="<?=$merchantDomainName; ?>">
        <input type="hidden" name="merchantSignature" value="<?=$merchantSignature; ?>">
        <input type="hidden" name="merchantTransactionType" value="AUTO">
        <input type="hidden" name="merchantTransactionSecureType" value="AUTO">
        <input type="hidden" name="orderReference" value="<?=$orderReference; ?>">
        <input type="hidden" name="orderDate" value="<?=$orderDate; ?>">
        <input type="hidden" name="amount" value="<?=$orderFullPrice; ?>">
        <input type="hidden" name="currency" value="<?=$orderCurrency; ?>">
        <?php /*input type="hidden" name="productName[]" value="Apple iPhone 6 16GB">
        <input type="hidden" name="productPrice[]" value="1">
        <input type="hidden" name="productCount[]" value="1"*/?>
        <?php
        if(!empty($wfp_p_names)){
            foreach ($wfp_p_names as $w_name) {
                echo '<input type="hidden" name="productName[]" value="' . $w_name . '">' . "\n";
            }
        }
        if(!empty($wfp_p_prices)){
            foreach ($wfp_p_prices as $w_price) {
                echo '<input type="hidden" name="productPrice[]" value="' . $w_price . '">' . "\n";
            }
        }
        if(!empty($wfp_p_qtys)){
            foreach ($wfp_p_qtys as $w_qty) {
                echo '<input type="hidden" name="productCount[]" value="' . $w_qty . '">' . "\n";
            }
        }
        ?>
        <input type="hidden" name="clientFirstName" value="<?=$model->user_name;?>">
        <input type="hidden" name="clientLastName" value=".">
        <input type="hidden" name="clientPhone" value="<?=(!empty($model->user_phone)) ? $model->user_phone : '380631234567';?>">
        <input type="hidden" name="clientEmail" value="<?=$model->user_email;?>">
        <input type="hidden" name="returnUrl" value="http://<?=$_SERVER['HTTP_HOST'];?>/cart/view/<?=$model->secret_key?>/success/">
        <input type="hidden" name="serviceUrl" value="http://<?=$_SERVER['HTTP_HOST'];?>/site/wfpresponse">
        <input type="hidden" name="language" value="<?=strtoupper(Yii::app()->language);?>">
        <button type="submit" style="visibility: hidden;" class="btn btn-special btn-color">Оплатить</button>
    </form>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function(){
$('.portmone').css('display','none');
<?php if($model->status_id != 6): ?>
    $('.cart4').css('display','none');
<?php elseif($model->status_id == 6): ?>
    $('.cart3').css('display','none');
    $('.cart4').css('display','block');
<?php endif; ?>
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
else if($($('.selected').children()[0]).attr('id')=="payment4") {
    // сохраняем $orderReference и ID заказа в БД
    $.post(
        '/site/wfporder',
        { orderReference : '<?=$orderReference;?>' }
    );
    // вызываем виджет – или отправляем форму
    <?=($wfp_type == 'widget') ? 'wfpay();' . "\n" : '$(\'.wayforpay\').submit();' . "\n";?>
}
})
});
</script>
