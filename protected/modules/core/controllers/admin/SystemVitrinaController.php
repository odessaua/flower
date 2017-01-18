<?php

/**
 * Manage system languages
 * @package core.systemLanguages
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
class SystemVitrinaController extends SAdminController
{

	public function actionIndex()
	{
		$model = new StoreProduct('search');

		if (!empty($_GET['CoreVitrina']))
			$model->attributes = $_GET['CoreVitrina'];

		// Pass additional params to search method.
		$params = array(
			'category'=>Yii::app()->request->getParam('category', null)
		);

		$dataProvider = $model->search($params);
		$dataProvider->pagination->pageSize = Yii::app()->settings->get('core', 'productsPerPageAdmin');

		$this->render('index', array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionCreate()
	{
		$this->actionUpdate(true);
	}

	public function actionUpdate($new = false)
	{
		if ($new === true)
			$model = new SSystemVitrina;
		else{

			$model = SSystemVitrina::model()->findByPk($_GET['id']);
		}

		if (!$model)
			throw new CHttpException(404, Yii::t('CoreModule.core', 'Витрина не найдена'));
		$form = new SAdminForm('application.modules.core.views.admin.systemVitrina.languageForm', $model);
		
		if (Yii::app()->request->isPostRequest)
		{
			$model->attributes = $_POST['SSystemVitrina'];
			$rand=rand(0,9999);
			
			if ($model->validate())
			{
			   $this->setFlashMessage(Yii::t('CoreModule.core', 'Изменения успешно сохранены'));

				if (isset($_POST['REDIRECT']))
					$this->smartRedirect($model);
				else
					$this->redirect(array('index'));
			}
			
		}

		$this->render('update', array(
			'model'=>$model,
			'form'=>$form,
		));
	}

	/**
	 * Delete language
	 */
	public function actionDelete()
	{
		if (Yii::app()->request->isPostRequest)
		{
			$model = SSystemVitrina::model()->findAllByPk($_REQUEST['id']);

			if(!empty($model))
			{
				foreach($model as $page)
					$page->delete();
			}

			if (!Yii::app()->request->isAjaxRequest)
				$this->redirect('index');
		}
	}

}