<?php
  echo "Заказ №: ".$order->id." в город ".$order->receiver_city."\n";
    echo "Дата доставки: ".$order->datetime_del."\n";
    echo "Сумма заказа: ".$order->total_price."\n\n";
    echo "Информация о получателе \n\n";
    echo "Город: ".$order->receiver_city."\n";
    echo "Имя: ".$order->receiver_name."\n";
    echo "Адрес: ".$order->user_address."\n";
    echo "Телефон моб: ".$order->phone1."\n";
    echo "Телефон дом: ".$order->phone2."\n";
    echo "Дополнительная информация: ".$order->user_comment."\n";
    echo "Текст открытки: ".$order->card_text."\n\n";
    echo "Информация о заказчике \n\n";
    echo "Имя: ".$order->user_name."\n";
    echo "Email: ".$order->user_email."\n";
    echo "Страна: ".$order->country."\n";
    echo "Город : ".$order->city."\n";
    echo "Адрес: ".$order->user_address."\n";
    echo "Телефон: ".$order->user_phone."\n\n";
    echo "Информация о Заказе \n\n"; 
	if ($order->doPhoto == 1)	
	echo "Сделать фото доставки \n";
	if ($order->do_card == 1)	
	echo "Открытка \n";
	 
	 foreach($model->getOrderedProducts()->getData() as $product): 
	 
      $pro_model = StoreProduct::model()->findByPk($product->product_id);
		echo $product->getRenderFullName(false);   echo " - ".$product->price."\n";
     endforeach
?>
