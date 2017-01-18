<?php

class SiteController extends Controller
{

	public function actionIndex()
	{
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', array('error'=>$error));
		}
	}
	
	public function actionAutocompleteCity() 
	{	
		$res =array();


		$lang= Yii::app()->language;
		if (isset($_GET['term'])) {
			$qtxt="";
			if($lang=="ru")
				$qtxt ="SELECT name FROM cityTranslate WHERE name LIKE :name AND language_id=1";
			elseif($lang=="en") {
				$qtxt ="SELECT name FROM cityTranslate WHERE name LIKE :name AND language_id=9";
			}
			elseif($lang=="ua") {
				$qtxt ="SELECT name FROM cityTranslate WHERE name LIKE :name AND language_id=10";
			}
			else
				$qtxt ="SELECT name FROM city WHERE name LIKE :name";
			// echo $lang;
			$command =Yii::app()->db->createCommand($qtxt);
			$command->bindValue(":name", '%'.$_GET['term'].'%', PDO::PARAM_STR);
			$res =$command->queryColumn();
		}
		// echo $command;
		echo CJSON::encode($res);
		Yii::app()->end();
	}
	
	public function actionChangeCity($city)
	{
		$app = Yii::app();
		
		if(!empty($city))
		{
			$app->session['_city'] =  $city;
		}else{
			$app->session['_city'] =  'Киев';
		}
		
		echo $app->session['_city'];
		Yii::app()->controller->refresh();
	}
	
	public function actionSetPaymentId()
	{
		$payment_id = $_GET['payment_id'];
		$order_id = $_GET['order_id'];
		
		$sql = "UPDATE `Order` SET payment_id = :payment_id WHERE id = :order_id";
		$command =Yii::app()->db->createCommand($sql);
		$command->bindValue(":payment_id", $payment_id, PDO::PARAM_INT);
		$command->bindValue(":order_id", $order_id, PDO::PARAM_INT);
		$command->query();
	}
	
	public function actionReviews()
	{
		$this->render('reviews');
	}
}
