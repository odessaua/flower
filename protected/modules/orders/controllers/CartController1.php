<?php

Yii::import('orders.models.*');
Yii::import('store.models.*');
 // ini_set('display_errors', 1);
 // ini_set('display_startup_errors', 1);
 // error_reporting(E_ALL);
/**
 * Cart controller
 * Display user cart and create new orders
 */
class CartController extends Controller
{

	/**
	 * @var OrderCreateForm
	 */
	public $form;

	/**
	 * @var bool
	 */
	protected $_errors = false;

	/**
	 * Display list of product added to cart
	 */
	public function actionIndex()
	{
		// Recount
		if(Yii::app()->request->isPostRequest && Yii::app()->request->getPost('recount') && !empty($_POST['quantities']))
			$this->processRecount();
		$total=0;
		$this->form = new OrderCreateForm;
		$rate=Yii::app()->currency->active['rate'];
		$symbol=Yii::app()->currency->active['symbol'];
		$photoPrice=StoreDeliveryMethod::model()->findByAttributes(array('id'=>17))['price'];
		$cardPrice=StoreDeliveryMethod::model()->findByAttributes(array('id'=>18))['price'];
		

		
		
		if(Yii::app()->request->isPostRequest && Yii::app()->request->getPost('create'))
		{
			if(isset($_POST['OrderCreateForm']))
			{
				$this->form->attributes = $_POST['OrderCreateForm'];
				$city=Yii::app()->session['_city'];
				if($this->form->validate())
				{
					// var_dump($_POST);die;
					$order = $this->createOrder();
					Yii::app()->cart->clear();
					$this->addFlashMessage(Yii::t('OrdersModule.core', 'Thank you. Your order is issued. Select a Payment Method.'));
					Yii::app()->request->redirect($this->createUrl('view', array('secret_key'=>$order->secret_key)));
				}
				
			}

		}
		$deliveryMethods = StoreDeliveryMethod::model()
			->applyTranslateCriteria()
			->active()
			->orderByName()
			->findAll();
		// var_dump($deliveryMethods);
		$this->render('index', array(
			'items'           => Yii::app()->cart->getDataWithModels(),
			'delivery_price'=>$deliveryPrice['delivery'],
			'totalPrice'      => Yii::app()->currency->convert(Yii::app()->cart->getTotalPrice()),
			'deliveryMethods' => $deliveryMethods,
			'photoPrice'=>$photoPrice,
			'cardPrice'=>$cardPrice,
			'rate'=>$rate,
			'symbol'=>$symbol
		));
	}

	/**
	 * Find order by secret_key and display.
	 * @throws CHttpException
	 */
	public function actionView()
	{
		$secret_key = Yii::app()->request->getParam('secret_key');
		$model = Order::model()->find('secret_key=:secret_key', array(':secret_key'=>$secret_key));
		$photoPrice=StoreDeliveryMethod::model()->findByAttributes(array('id'=>17))['price'];
		$cardPrice=StoreDeliveryMethod::model()->findByAttributes(array('id'=>18))['price'];
		$deliveryPrice=Yii::app()->db->createCommand()
							     ->select("c.delivery")
							     ->from("city c")
							     ->join('cityTranslate ct','ct.object_id=c.id')
							     ->where('ct.name=:name', array(':name'=>Yii::app()->session['_city']))
							     // ->where('ct.name=:name',':name'=>)_
							     ->queryRow();
		if(!empty($model->doPhoto)){$model->photo_price=$photoPrice;}
		if(!empty($model->do_card)){$model->card_price=$cardPrice;}
		$model->delivery_price=$deliveryPrice['delivery'];
		$model->update();
		$rate=Yii::app()->db->createCommand()
							     ->select("rate")
							     ->from("StoreCurrency")
							     ->where('id=2')
							     ->queryRow()['rate'];
		$symbol=Yii::app()->currency->active['symbol'];
		if(!$model)
			throw new CHttpException(404, Yii::t('OrdersModule.core', 'Error. Order not found'));
		
		$this->render('view', array(
			'model'=>$model,
			'photoPrice'=>$photoPrice,
			'cardPrice'=>$cardPrice,
			'rate'=>$rate,
			'symbol'=>$symbol
		));
	}
	public function actionSuccess(){

		$secret_key = Yii::app()->request->getParam('secret_key');
		$model = Order::model()->find('secret_key=:secret_key', array(':secret_key'=>$secret_key));
		$photoPrice=StoreDeliveryMethod::model()->findByAttributes(array('id'=>17))['price'];
		$cardPrice=StoreDeliveryMethod::model()->findByAttributes(array('id'=>18))['price'];
		$deliveryPrice=Yii::app()->db->createCommand()
							     ->select("c.delivery")
							     ->from("city c")
							     ->join('cityTranslate ct','ct.object_id=c.id')
							     ->queryRow();
		if(!empty($model->doPhoto)){$model->photo_price=$photoPrice;}
		if(!empty($model->do_card)){$model->card_price=$cardPrice;}
		$model->delivery_price=$deliveryPrice['delivery'];
		$model->status_id=6;
		$model->update();
		$rate=Yii::app()->db->createCommand()
							     ->select("rate")
							     ->from("StoreCurrency")
							     ->where('id=2')
							     ->queryRow()['rate'];
		$symbol=Yii::app()->currency->active['symbol'];
		if(!$model)
			throw new CHttpException(404, Yii::t('OrdersModule.core', 'Error. Order not found'));
		
			$this->render('success', array(
				'model'=>$model,
				'photoPrice'=>$photoPrice,
				'cardPrice'=>$cardPrice,
				'rate'=>$rate,
				'symbol'=>$symbol
			));
	}
	/**
	 * Validate POST data and add product to cart
	 */
	
	public function actionAdd()
	{
		$variants = array();

		// Load product model
		$model = StoreProduct::model()
			->active()
			->findByPk(Yii::app()->request->getPost('product_id', 0));

		// Check product
		if(!isset($model))
			$this->_addError(Yii::t('OrdersModule.core', 'Error. Product not found'), true);
		
		//if product isset in Region
		$city = 908;
		$cityName = "Киев";
		
		if(isset(Yii::app()->session['_city']))
		{
			$cityName = Yii::app()->session['_city'];
			
			$sql = "SELECT id FROM city WHERE name = :name";
			$command =Yii::app()->db->createCommand($sql);
			$command->bindValue(":name", $cityName, PDO::PARAM_STR);
			$res =$command->queryRow();
			
			if($res['id'])
				$city = $res['id'];	
		}
		
		Yii::import('application.modules.store.models.StoreProductCityRef');
		$productInCity = StoreProductCityRef::model()->find(array('condition'=>'product_id='.$model->id.' AND city_id='.$city));
		if(!$productInCity)
			$this->_addError(Yii::t('OrdersModule.core', 'Product not available for your region'.$cityName), true);
		
		// Update counter
		$model->added_to_cart_count += 1;
		$model->saveAttributes(array('added_to_cart_count'));

		// Process variants
		if(!empty($_POST['eav']))
		{
			foreach($_POST['eav'] as $attribute_id=>$variant_id)
			{
				if(!empty($variant_id))
				{
					// Check if attribute/option exists
					if(!$this->_checkVariantExists($_POST['product_id'], $attribute_id, $variant_id))
						$this->_addError(Yii::t('OrdersModule.core', 'Error. Product variant not found'));
					else
						array_push($variants, $variant_id);
				}
			}
		}

		// Process configurable products
		if($model->use_configurations)
		{
			// Get last configurable item
			$configurable_id = Yii::app()->request->getPost('configurable_id', 0);

			if(!$configurable_id || !in_array($configurable_id , $model->configurations))
				$this->_addError(Yii::t('OrdersModule.core', 'Error. Choose product variant'), true);
		}else
			$configurable_id  = 0;
		// var_dump($variants);isset($variants)?die:"";
		Yii::app()->cart->add(array(
			'product_id'      => $model->id,
			'variants'        => $variants,
			'configurable_id' => $configurable_id,
			'quantity'        => (int) Yii::app()->request->getPost('quantity', 1),
			'price'           => $model->price,
		));

		$this->_finish();
	}
	
	/**
	 * Remove product from cart and redirect
	 */
	public function actionRemove($index)
	{
		Yii::app()->cart->remove($index);

		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->request->redirect($this->createUrl('index'));
	}

	/**
	 * Clear cart
	 */
	public function actionClear()
	{
		Yii::app()->cart->clear();

		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->request->redirect($this->createUrl('index'));
	}

	/**
	 * Render data to display in theme header.
	 */
	public function actionRenderSmallCart()
	{
		$this->renderPartial('_small_cart');
	}
	
	/**
	 * Render data to display in popup.
	 */
	public function actionRenderPopupCart()
	{
		$this->renderPartial('_popup_cart');
	}

	/**
	 * Create new order
	 * @return Order
	 */

	public function createOrder()
	{
		if(Yii::app()->cart->countItems() == 0)
			return false;

		$order = new Order;
		
		if(isset(Yii::app()->session['_city']))
			$receiver_city = Yii::app()->session['_city'];
		else
			$receiver_city = "Киев";

		// Set main data
		$order->user_id      = Yii::app()->user->isGuest ? null : Yii::app()->user->id;
		$order->user_name    = $this->form->name;
		$order->country = $this->form->country;
		$order->city = $this->form->city;
		$order->user_email   = $this->form->email;
		$order->user_phone   = $this->form->phone;
		$order->user_address = $this->form->address;
		$order->user_comment = $this->form->comment;
		
		$order->receiver_name = $this->form->receiver_name;
		$order->receiver_city = $receiver_city;
		$order->phone1 = $this->form->phone1;
		$order->phone2 = $this->form->phone2;
		$order->datetime_del = $this->form->datetime_delivery;
		$order->doPhoto = $this->form->doPhoto;
		$order->do_card = $this->form->do_card;
		$order->card_text = $this->form->card_text;
		$order->not_disturb = $this->form->not_disturb;
		

		if($order->validate())
			$order->save();
		else
			throw new CHttpException(503, Yii::t('OrdersModule.core', 'Creating order error'));

		// Process products
		foreach(Yii::app()->cart->getDataWithModels() as $item)
		{
			$ordered_product = new OrderProduct;
			$ordered_product->order_id        = $order->id;
			$ordered_product->product_id      = $item['model']->id;
			$ordered_product->configurable_id = $item['configurable_id'];
			$ordered_product->name            = $item['model']->name;
			$ordered_product->quantity        = $item['quantity'];
			$ordered_product->sku             = $item['model']->sku;
			$ordered_product->price           = StoreProduct::calculatePrices($item['model'], $item['variant_models'], $item['configurable_id']);

			// Process configurable product
			if(isset($item['configurable_model']) && $item['configurable_model'] instanceof StoreProduct)
			{
				$configurable_data=array();

				$ordered_product->configurable_name = $item['configurable_model']->name;
				// Use configurable product sku
				$ordered_product->sku = $item['configurable_model']->sku;
				// Save configurable data

				$attributeModels = StoreAttribute::model()->findAllByPk($item['model']->configurable_attributes);
				foreach($attributeModels as $attribute)
				{
					$method = 'eav_'.$attribute->name;
					$configurable_data[$attribute->title]=$item['configurable_model']->$method;
				}
				$ordered_product->configurable_data=serialize($configurable_data);
			}

			// Save selected variants as key/value array
			if(!empty($item['variant_models']))
			{
				$variants = array();
				foreach($item['variant_models'] as $variant)
					$variants[$variant->attribute->title] = $variant->option->value;
				$ordered_product->variants = serialize($variants);
			}

			$ordered_product->save();
		}

		// Reload order data.
		$order->refresh();

		// All products added. Update delivery price.
		$order->updateDeliveryPrice();

		// Send email to user.
		$this->sendEmail($order);
		$this->sendEmailAdmin($order,Yii::app()->params['adminEmail']);
		//$this->sendEmailAdmin($order,'7roses.office@gmail.com');
		return $order;
	}

	/**
	 * Check if product variantion exists
	 * @param $product_id
	 * @param $attribute_id
	 * @param $variant_id
	 * @return string
	 */
	protected function _checkVariantExists($product_id, $attribute_id, $variant_id)
	{
		return StoreProductVariant::model()->countByAttributes(array(
			'id'           => $variant_id,
			'product_id'   => $product_id,
			'attribute_id' => $attribute_id
		));
	}

	/**
	 * Recount product quantity and redirect
	 */
	public function processRecount()
	{
		Yii::app()->cart->recount(Yii::app()->request->getPost('quantities'));

		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->request->redirect($this->createUrl('index'));
	}

	/**
	 * Add message to errors array.
	 * @param string $message
	 * @param bool $fatal finish request
	 */
	protected function _addError($message, $fatal = false)
	{
		if($this->_errors === false)
			$this->_errors = array();

		array_push($this->_errors, $message);

		if($fatal === true)
			$this->_finish();
	}

	/**
	 * Process result and exit!
	 */
	protected function _finish()
	{
		echo CJSON::encode(array(
			'errors'=>$this->_errors,
			'message'=>Yii::t('OrdersModule.core','Product successfully added in {cart}', array(
				'{cart}'=>CHtml::link(Yii::t('OrdersModule', 'корзину'), array('/orders/cart/index'))
			)),
		));
		exit;
	}

	/**
	 * Sends email to user after create new order.
	 */
	private function sendEmail(Order $order)
	{
		$theme=Yii::t('OrdersModule.core', 'Your order #').$order->id;

		$lang=Yii::app()->language;
		$emailBodyFile=Yii::getPathOfAlias("application.emails.$lang").DIRECTORY_SEPARATOR.'new_order.php';
		
		// If template file does not exists use default russian translation
		if(!file_exists($emailBodyFile))
			$emailBodyFile=Yii::getPathOfAlias("application.emails.ru").DIRECTORY_SEPARATOR.'new_order.php';
		$body = $this->renderFile($emailBodyFile, array('order'=>$order), true);

		$mailer           = Yii::app()->mail;
		// $mailer->IsSMTP();
		$mailer->From     = Yii::app()->params['adminEmail'];
		$mailer->FromName = Yii::app()->settings->get('core', 'siteName');
		$mailer->Subject  = $theme;
		$mailer->Body     = $body;
		$mailer->AddAddress($order->user_email);
		$mailer->AddReplyTo(Yii::app()->params['adminEmail']);
		$mailer->isHtml(true);
		$mailer->Send();
		$mailer->ClearAddresses();
	}
	private function sendEmailAdmin(Order $order,$address)
	{
		$theme=Yii::t('OrdersModule.core', 'New order #').$order->id;

		$lang=Yii::app()->language;
		$emailBodyFile=Yii::getPathOfAlias("application.emails.ru").DIRECTORY_SEPARATOR.'admin_order.php';
		
		// If template file does not exists use default russian translation
		if(!file_exists($emailBodyFile))
			$emailBodyFile=Yii::getPathOfAlias("application.emails.ru").DIRECTORY_SEPARATOR.'admin_order.php';
		$body = $this->renderFile($emailBodyFile, array('order'=>$order), true);

		$mailer           = Yii::app()->mail;
		$mailer->From     = Yii::app()->params['adminEmail'];
		$mailer->FromName = Yii::app()->settings->get('core', 'siteName');
		$mailer->Subject  = $theme;
		$mailer->Body     = $body;
		$mailer->AddAddress($address);
		// $mailer->AddAddress('7roses.office@gmail.com');_
		$mailer->AddReplyTo(Yii::app()->params['adminEmail']);
		$mailer->isHtml(false);
		$mailer->AddCC('office@odessa-guide.com', 'Andrey');
		$mailer->SetMessageType = 'plain';
		$mailer->Send();
		$mailer->ClearAddresses();
	}
	public function actionPhone()
	{
		// $theme=Yii::t('OrdersModule', 'Ваш заказ #').$order->id;

		$lang=Yii::app()->language;
		$emailBodyFile=Yii::getPathOfAlias("application.emails.$lang").DIRECTORY_SEPARATOR.'new_order_admin.php';
		
		// If template file does not exists use default russian translation
		if(!file_exists($emailBodyFile))
			$emailBodyFile=Yii::getPathOfAlias("application.emails.ru").DIRECTORY_SEPARATOR.'new_order_admin.php';
		$body = $this->renderFile($emailBodyFile, array(
			'username'=>$_POST['username'],'phone'=>$_POST['phone'],'email'=>$_POST['email'],
			'id'=>$_POST['id'],'quantity'=>$_POST['quantity']
			), true);
		$mailer           = Yii::app()->mail;
		$mailer->From     = Yii::app()->params['adminEmail'];
		$mailer->FromName = Yii::app()->settings->get('core', 'siteName');
		$mailer->Subject  = $theme;
		$mailer->Body     = $body;
		$mailer->AddAddress($_POST['email']);
		$mailer->AddReplyTo(Yii::app()->params['adminEmail']);
		$mailer->isHtml(true);
		$mailer->Send();
		$mailer->ClearAddresses();
	}
}
