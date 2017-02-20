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
				$qtxt ="SELECT object_id, name FROM cityTranslate WHERE name LIKE :name AND language_id=1";
			elseif($lang=="en") {
				$qtxt ="SELECT object_id, name FROM cityTranslate WHERE name LIKE :name AND language_id=9";
			}
			elseif($lang=="ua") {
				$qtxt ="SELECT object_id, name FROM cityTranslate WHERE name LIKE :name AND language_id=10";
			}
			else
				$qtxt ="SELECT id as object_id, name FROM city WHERE name LIKE :name";
			// echo $lang;
			$command =Yii::app()->db->createCommand($qtxt);
			$command->bindValue(":name", '%'.$_GET['term'].'%', PDO::PARAM_STR);
			//$res =$command->queryColumn();
			$results =$command->queryAll();
		}

        if(!empty($results)){
            $langs = array(
                'ru' => 1,
                'en' => 9,
                'ua' => 10,
            );
            $ids = array();
            foreach ($results as $row) {
                $ids[] = $row['object_id'];
            }
            if(!empty($ids)){
                $sql_lang = (!empty($langs[Yii::app()->language])) ? " AND rt.language_id = " . $langs[Yii::app()->language] : "";
                $sql = "SELECT c.id, c.region_id, rt.name as rt_name, r.name
                        FROM city c
                        LEFT JOIN regionTranslate rt ON rt.object_id = c.region_id " . $sql_lang . "
                        LEFT JOIN region r ON r.id = c.region_id
                        WHERE c.id IN (" . implode(",", $ids) . ")";
                $comm = Yii::app()->db->createCommand($sql);
                $rr = $comm->queryAll();
                if(!empty($rr)){
                    $rr = CArray::toolIndexArrayBy($rr, 'id');
                    foreach ($results as $r_row) {
                        $name = (!empty($rr[$r_row['object_id']]['rt_name'])) ? $rr[$r_row['object_id']]['rt_name'] : $rr[$r_row['object_id']]['name'];
                        $res[] = $r_row['name'] . ((!empty($name)) ? ' (' . $name . ')' : '');
                    }
                }
            }

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
            $city_ex = explode(' (', $city); // for entries like Ужгород (Zakarpattia Region)
			$app->session['_city'] =  $city_ex[0];
            // получаем object_id города
            $trans_sql = "SELECT `object_id` FROM `cityTranslate` WHERE `name` = :name";
            $trans_command =Yii::app()->db->createCommand($trans_sql);
            $trans_command->bindValue(":name", $city_ex[0], PDO::PARAM_STR);
            $trans_res = $trans_command->queryScalar();
            // получаем название города из `city`
            $city_sql = "SELECT `name` FROM `city` WHERE `id` = " . (int)$trans_res;
            $city_command = Yii::app()->db->createCommand($city_sql);
            $city_res = $city_command->queryScalar();
            $city_url = CSlug::url_slug($city_res);
		}else{
			$app->session['_city'] =  'Киев';
		}
		
		echo $app->session['_city'] . ((!empty($city_url)) ? '_' . $city_url : '');
		//Yii::app()->controller->refresh();
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

    // serviceUrl: 'http://flowers3.loc/site/wfpresponse'
    public function actionWfpResponse()
    {
        $json = file_get_contents('php://input');
        if(!empty($json)){
//            $obj = json_decode($json, TRUE);
//            print_r($obj);
            $sql = "INSERT INTO `test` SET `data` = :json";
            $command =Yii::app()->db->createCommand($sql);
            $command->bindValue(":json", $json, PDO::PARAM_STR);
            $command->query();
            echo json_encode(array('status' => 'Ok', 'code' => '1100'));
        }
        else{
            echo json_encode(array('status' => 'False', 'code' => '1111'));
        }
    }

    /**
     * Получаем и обновляем статус заказа в системе WayForPay
     * @param $order_ref orderReference заказа в системе WayForPay
     * @return bool
     */
    public function actionWfpStatus($order_ref)
    {
        $string = Yii::app()->params['merchantAccount'] . ";" . $order_ref;
        $merchantSignature = hash_hmac("md5", $string, Yii::app()->params['merchantSecretKey']);
        $data = array(
            'transactionType' => 'CHECK_STATUS',
            'merchantAccount' => Yii::app()->params['merchantAccount'],
            'orderReference' => $order_ref,
            'merchantSignature' => $merchantSignature,
            'apiVersion' => 1,
        );

        $order_ex = explode('_', $order_ref);
        $order_id = end($order_ex);

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, 'https://api.wayforpay.com/api');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            $out = curl_exec($curl);//var_dump($out);
            curl_close($curl);

            if(CJsn::isJson($out)){
                //var_dump(json_decode($out, true));
                $response = json_decode($out, true);
                $wfp_order = WfpOrder::model()->findByAttributes(array('order_id' => $order_id));
                if(!empty($wfp_order) && !empty($response['orderReference'])){
                    unset($response['orderReference'], $response['merchantAccount']);
                    $response['createdDate'] = (!empty($response['createdDate'])) ? date('Y-m-d H:i:s', $response['createdDate']) : '0000-00-00 00:00:00';
                    $response['processingDate'] = (!empty($response['processingDate'])) ? date('Y-m-d H:i:s', $response['processingDate']) : '0000-00-00 00:00:00';
                    WfpOrder::model()->updateByPk($wfp_order->id, $response);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Сохраняем ID заказа и orderReference в БД
     */
    public function actionWfpOrder()
    {
        if(!empty($_POST)){
            $orderReference = Yii::app()->request->getPost('orderReference', '');
            if(!empty($orderReference)){
                $order_ex = explode('_', $orderReference);
                $order_id = end($order_ex);
                $wfp_order = WfpOrder::model()->findByAttributes(array('order_id' => $order_id));
                if(!empty($wfp_order)) return; // сохраняем первую запись о заказе, с оригинальным order_reference
                //WfpOrder::model()->deleteAllByAttributes(array('order_id' => $order_id));

                $model = new WfpOrder();
                $model->order_id = $order_id;
                $model->orderReference = $orderReference;
                $model->save();
            }
        }
    }

}
