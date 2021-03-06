<?php

/**
 * Model to handle feedback form
 */
class FeedbackForm extends CFormModel
{

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var string
	 */
	public $message;

	/**
	 * @var string
	 */
	public $code;

	/**
	 * Initialize model
	 */
	public function init()
	{
		$user=Yii::app()->user;
		if($user->isGuest===false)
		{
			$this->name=$user->username;
			$this->email=$user->email;
		}
	}

	/**
	 * Validation rules
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('name, email, message', 'required'),
			array('email', 'filter', 'filter'=>'trim'),
			array('email', 'email'),			
			array('message', 'length', 'max'=>Yii::app()->settings->get('feedback', 'max_message_length')),
			array('code','captcha','allowEmpty'=>!Yii::app()->settings->get('feedback', 'enable_captcha')),
		);
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'name'=>Yii::t('FeedbackModule.core', 'Ваше имя'),
			'email'=>Yii::t('FeedbackModule.core', 'Email'),
			'message'=>Yii::t('FeedbackModule.core', 'Сообщение'),
		);
	}

	/**
	 * Send email
	 */
	public function sendMessage()
	{
		$mailer           = Yii::app()->mail;
		$mailer->From     = $this->email;
		$mailer->FromName = Yii::t('FeedbackModule.core', 'Форма обратной связи');
		$mailer->Subject  = Yii::t('FeedbackModule.core', 'Сообщение от {name}', array('{name}'=>CHtml::encode($this->name)));
		$mailer->Body     = CHtml::encode($this->message);
		$mailer->AddAddress(Yii::app()->settings->get('feedback', 'admin_email'));
		$mailer->AddReplyTo($this->email);
		$mailer->Send();
		$YourName = $this->name;
		Yii::app()->user->setFlash('messages', Yii::t('FeedbackModule.core','Thank you $YourName! Your message has been sent.'));
	}

}
