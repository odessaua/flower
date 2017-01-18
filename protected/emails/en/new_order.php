<html>
<body>

  <p>Dear, <?=$order->user_name?>.</p>
  <p>Thank you for placing order with 7Roses.</p>
  <p>Your order number is #<?=$order->id?>.</p>

  <p>
    Please, check the details of your order here::<br>
   <a href="<?= $this->createAbsoluteUrl('view', array('secret_key'=>$order->secret_key)) ?>">
     <?= $this->createAbsoluteUrl('view', array('secret_key'=>$order->secret_key)) ?>
   </a>
  </p>

  <p>
    <ul>
    <?php foreach ($order->products as $product)
        echo '<li>'.$product->getRenderFullName()."</li>";
    ?>
    </ul>
    
    <p>
      <b>Total to pay:</b>
      <?=StoreProduct::formatPrice($order->total_price + $order->delivery_price)?> <?=Yii::app()->currency->main->symbol?>
    </p>

    <p>
      <b>Best Regards,<br/>
    7Roses, Odessa<br/>
    Ukraine
    </p>

  </p>
</body>
</html>