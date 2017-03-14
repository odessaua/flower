<?php

class DefaultController extends Controller
{

	/**
	 * @return array
	 */
	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
			),
		);
	}

	/**
	 * Display feedback form
	 */
	public function actionIndex()
	{
		Yii::import('feedback.models.FeedbackForm');
		$model = new FeedbackForm;

		if(isset($_POST['FeedbackForm']))
			$model->attributes = $_POST['FeedbackForm'];
		
		if(Yii::app()->request->isPostRequest )
		{
			$model->validate();
			$model->sendMessage();
			// Yii::app()->request->redirect($this->createUrl('index'));
		}

        // Other cities
        Yii::import('feedback.models.CityTranslate');
        $criteria = new CDbCriteria();
        $criteria->condition = "`language_id` = " . (int)$this->language_info->id . " AND (`firm_name` != '' AND `firm_name` IS NOT NULL)";
        $criteria->order = '`name` ASC';
        $addresses = CityTranslate::model()->findAll($criteria);

		$this->render('index', array(
			'model'=>$model,
			'addresses' => $addresses,
		));
	}

}
