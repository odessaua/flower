<?php

/**
 * Profile, order and other user data.
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
class ProfileController extends Controller
{

	/**
	 * Check if user is authenticated
	 * @return bool
	 * @throws CHttpException
	 */
	public function beforeAction($action)
	{
		if(Yii::app()->user->isGuest)
			throw new CHttpException(404, Yii::t('UsersModule.core', 'Access error'));
		return true;
	}

	/**
	 * Display profile start page
	 */
	public function actionIndex()
	{
		Yii::import('application.modules.users.forms.ChangePasswordForm');
		$request=Yii::app()->request;


		$user=Yii::app()->user->getModel();
		$profile=$user->profile;
		$changePasswordForm=new ChangePasswordForm();
		$changePasswordForm->user=$user;

		if(Yii::app()->request->isPostRequest)
		{
			if($request->getPost('UserProfile') || $request->getPost('User'))
			{
				$profile->attributes=$request->getPost('UserProfile');
				$user->email=isset($_POST['User']['email']) ? $_POST['User']['email'] : null;

				$valid=$profile->validate();
				$valid=$user->validate() && $valid;

				if($valid)
				{
					$user->save();
					$profile->save();

					$this->addFlashMessage(Yii::t('UsersModule.core', 'Changes successfully saved'));
					$this->refresh();
				}
			}

			if($request->getPost('ChangePasswordForm'))
			{
				$changePasswordForm->attributes=$request->getPost('ChangePasswordForm');
				if($changePasswordForm->validate())
				{
					$user->password=User::encodePassword($changePasswordForm->new_password);
					$user->save(false);
					$this->addFlashMessage(Yii::t('UsersModule.core', 'Password successfully saved.'));
					$this->refresh();
				}
			}
		}

		$this->render('index', array(
			'user'=>$user,
			'profile'=>$profile,
			'changePasswordForm'=>$changePasswordForm
		));
	}
	
	/**
	 * Display user orders
	 */
	public function actionOrders()
	{
		Yii::import('application.modules.orders.models.*');
		// Yii::import('application.modules.order.models.*');;
		Yii::import('application.modules.store.models.*');
		$uid=Yii::app()->user->getId();
		$orders=new CActiveDataProvider(Order::model(),
      array(
     'criteria'=>array('with'=>array('products'=>array('condition'=>'t.id=products.order_id')),'condition' => 't.user_id='.Yii::app()->user->getId()),
     // 'pagination' => array('pageSize'=>Yii::app()->params['productsPerPage']),
    )
);
		$this->render('orders', array(
			'orders'=>$orders,
			// 'products'=>$product,
		));
	}

}
