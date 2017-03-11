<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
/**
 * Admin delivery methods
 */
class DeliveryRegionsController extends SAdminController {


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->actionUpdate(true);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($new = false)
	{
		if ($new === true)
		{
			$model=new City;
			//$model->unsetAttributes();
		}
		else
		{
			$model = City::model()->language($_GET)->findByPk($_GET['id']);
		}
		
		if (!$model)
			throw new CHttpException(404, Yii::t('StoreModule.admin', 'Регион доставки не найден.'));


		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$form = new STabbedForm('application.modules.store.views.admin.deliveryRegions._form', $model);
		if(isset($_POST['City']))
		{
			$model->attributes=$_POST['City'];
			if($model->validate())
			{
				$model->save();
				if (isset($_POST['REDIRECT']))
					$this->smartRedirect($model);
				else
					$this->redirect(array('index'));
				
				
				$this->setFlashMessage(Yii::t('StoreModule.admin', 'Изменения успешно сохранены'));
				$this->redirect(array('index'));
				
				// 
				
			}
				
		}

		$this->render('update',array(
			'model'=>$model,
			'form'=>$form
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new City('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['City']))
			$model->attributes=$_GET['City'];

		$dataProvider = $model->language(1)->search();
		$dataProvider->pagination->pageSize = Yii::app()->settings->get('core', 'productsPerPageAdmin');
        $dataProvider->sort->attributes->name = 'translate.name';

		$this->render('admin',array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Delete method
	 * @param array $id
	 */
	public function actionDelete($id = array())
	{
		if (Yii::app()->request->isPostRequest)
		{
			$model = City::model()->findAllByPk($_REQUEST['id']);

			if (!empty($model))
			{
				foreach($model as $m)
				{
					/*if($m->countProducts() == 0)*/
						$m->delete();
					/*else
						throw new CHttpException(409, Yii::t('OrdersModule.admin','Ошибка удаления способа доставки. Он используется заказами.'));*/
				}
			}

			if (!Yii::app()->request->isAjaxRequest)
				$this->redirect('index');
		}
	}
	
	
}
		
	