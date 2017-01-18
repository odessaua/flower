<?php

/**
 * View user orders
 * @var $orders
 */

$this->pageTitle=Yii::t('OrdersModule.core', 'My orders');
?>
<h1 class="has_background"><?php echo Yii::t('OrdersModule.core', 'My orders'); ?></h1>

<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'           => 'ordersListGrid',
		'dataProvider' => $orders,
		'template'     => '{items}',
		'columns' => array(
			array(
				'name'=>'user_name',
				'type'=>'raw',
				'value'=>'CHtml::link(CHtml::encode($data->user_name), array("/orders/cart/view", "secret_key"=>$data->secret_key))',
			),
			'receiver_name',
			'datetime_del',
			'receiver_city',
			'country',
			array(
				'name'=>'status_id',
				'filter'=>CHtml::listData(OrderStatus::model()->orderByPosition()->findAll(), 'id', 'name'),
				'value'=>'$data->status_name'
			),
			array(
				'name'=>'id',
				'filter'=>CHtml::listData(StoreDeliveryMethod::model()->orderByPosition()->findAll(), 'id', 'name'),
				'value'=>'$data->id'
			),
			array(
				'type'=>'raw',
				'name'=>'full_price',
				'value'=>'StoreProduct::formatPrice($data->full_price)',
			),
				array(
					'name'=>Yii::t("OrdersModule.core","Products"),
					'value'=>'OrderProduct::getProducts($data->products)'
				)
		),
	));
?>