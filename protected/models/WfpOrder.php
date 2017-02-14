<?php

/**
 * This is the model class for table "WfpOrder".
 *
 * The followings are the available columns in table 'WfpOrder':
 * @property string $id
 * @property integer $order_id
 * @property string $order_reference
 * @property string $reason
 * @property integer $reason_code
 * @property double $amount
 * @property string $currency
 * @property string $created_date
 * @property string $processing_date
 * @property string $card_pan
 * @property string $card_type
 * @property string $bank_country
 * @property string $bank_name
 * @property string $transaction_status
 * @property string $auth_code
 * @property double $refund_amount
 * @property string $settlement_date
 * @property double $settlement_amount
 * @property double $fee
 * @property string $merchant_signature
 */
class WfpOrder extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'WfpOrder';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, reason_code', 'numerical', 'integerOnly'=>true),
			array('amount, refund_amount, settlement_amount, fee', 'numerical'),
			array('order_reference, card_type', 'length', 'max'=>20),
			array('reason, card_pan, bank_country, bank_name, transaction_status, auth_code, merchant_signature', 'length', 'max'=>255),
			array('currency', 'length', 'max'=>10),
			array('settlement_date', 'length', 'max'=>50),
			array('created_date, processing_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_id, order_reference, reason, reason_code, amount, currency, created_date, processing_date, card_pan, card_type, bank_country, bank_name, transaction_status, auth_code, refund_amount, settlement_date, settlement_amount, fee, merchant_signature', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'order_id' => 'Order',
			'order_reference' => 'Order Reference',
			'reason' => 'Reason',
			'reason_code' => 'Reason Code',
			'amount' => 'Amount',
			'currency' => 'Currency',
			'created_date' => 'Created Date',
			'processing_date' => 'Processing Date',
			'card_pan' => 'Card Pan',
			'card_type' => 'Card Type',
			'bank_country' => 'Bank Country',
			'bank_name' => 'Bank Name',
			'transaction_status' => 'Transaction Status',
			'auth_code' => 'Auth Code',
			'refund_amount' => 'Refund Amount',
			'settlement_date' => 'Settlement Date',
			'settlement_amount' => 'Settlement Amount',
			'fee' => 'Fee',
			'merchant_signature' => 'Merchant Signature',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('order_reference',$this->order_reference,true);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('reason_code',$this->reason_code);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('processing_date',$this->processing_date,true);
		$criteria->compare('card_pan',$this->card_pan,true);
		$criteria->compare('card_type',$this->card_type,true);
		$criteria->compare('bank_country',$this->bank_country,true);
		$criteria->compare('bank_name',$this->bank_name,true);
		$criteria->compare('transaction_status',$this->transaction_status,true);
		$criteria->compare('auth_code',$this->auth_code,true);
		$criteria->compare('refund_amount',$this->refund_amount);
		$criteria->compare('settlement_date',$this->settlement_date,true);
		$criteria->compare('settlement_amount',$this->settlement_amount);
		$criteria->compare('fee',$this->fee);
		$criteria->compare('merchant_signature',$this->merchant_signature,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WfpOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
