<style type="text/css">
	div.userData input[type=text] {
		width: 385px;
	}
	div.userData textarea {
		width: 385px;
	}
	#orderedProducts {
		padding: 0 0 5px 0;
	}
	.ui-dialog .ui-dialog-content {
		padding: 0;
	}
	#dialog-modal .grid-view {
		padding: 0;
	}
	#orderSummaryTable tr td {
		padding: 3px;
	}
</style>

<div class="form wide padding-all">
	<?php if($model->isNewRecord)
		$action='create';
	else
		$action='update';
	echo CHtml::form($this->createUrl($action, array('id'=>$model->id)), 'post', array('id'=>'orderUpdateForm','enctype'=>'multipart/form-data'));

	if($model->hasErrors())
		echo CHtml::errorSummary($model);
	
if (isset($_REQUEST['id']))
echo CHtml::link('Импорт Заказа',array('/orders/admin/orders/import','id'=>$_REQUEST['id']));

	?>
<?php echo CHtml::form('','post',array('enctype'=>'multipart/form-data')); ?>

	<table width="100%">
		<tr valign="top">
			<td width="50%">
				<!-- User data -->
				<div class="userData">
					<?php if(!$model->isNewRecord): ?>
						<h4><?php
							echo Yii::t('OrdersModule.admin', 'Данные пользователя');
							echo "<br>";
							echo "<br>";
							echo "<br>";
							echo "IP Address: " . $geoinfo['ipAddress'];
							echo "<br>";
							echo "Country: " . $geoinfo['countryName'];
							echo "<br>";
							echo "Region: " . $geoinfo['regionName'];
							echo "<br>";
							echo "City: " . $geoinfo['cityName'];
							echo "<br>";
							echo "Latitude: " . $geoinfo['latitude'];
							echo "<br>";
							echo "Longitude: " . $geoinfo['longitude'];
							echo "<br>";
							echo !empty($model['user_id'])?"Пользователь зарегистрирован":"Пользователь не зарегистрирован";
							echo "<br>";

							 ?></h4>
					<?php endif;?>
					<div class="row">
						<?php echo CHtml::activeLabel($model,'status_id', array('required'=>true)); ?>
						<?php echo CHtml::activeDropDownList($model, 'status_id', CHtml::listData($statuses, 'id', 'name')); ?>
					</div>

					<div class="row">
						<?php echo CHtml::activeLabel($model,'paid'); ?>
						<?php echo CHtml::activeCheckBox($model, 'paid'); ?>
					</div>

					<div class="row">
						<?php echo CHtml::activeLabel($model,'user_name', array('required'=>true)); ?>
						<?php echo CHtml::activeTextField($model,'user_name'); ?>
						<?php if($model->user_id): ?>
						<div class="hint">
							<?php echo CHtml::link(Yii::t('OrdersModule.admin', 'Редактировать пользователя'), array(
								'/users/admin/default/update',
								'id'=>$model->user_id,
							));
							?>
						</div>
						<?php endif; ?>
					</div>
					
					<div class="row">
						<?php echo CHtml::activeLabel($model,'country', array('required'=>true)); ?>
						<?php echo CHtml::activeTextField($model,'country'); ?>
					</div>
					
					<div class="row">
						<?php echo CHtml::activeLabel($model,'city', array('required'=>true)); ?>
						<?php echo CHtml::activeTextField($model,'city'); ?>
					</div>

					<div class="row">
						<?php echo CHtml::activeLabel($model,'user_email', array('required'=>true)); ?>
						<?php echo CHtml::activeTextField($model,'user_email'); ?>
					</div>

					<div class="row">
						<?php echo CHtml::activeLabel($model,'user_phone'); ?>
						<?php echo CHtml::activeTextField($model,'user_phone'); ?>
					</div>
					
					<!-- Получатель -->
					
					<div class="row">
						<?php echo CHtml::activeLabel($model,'receiver_name'); ?>
						<?php echo CHtml::activeTextField($model,'receiver_name'); ?>
					</div>
					<div class="row">
					<?php if($model->isNewRecord){ ?>
						
							<?php echo CHtml::activeLabel($model,'receiver_city'); ?>
							<?php echo CHtml::dropDownList("receiver_city",Yii::app()->db->createCommand()
								     ->select("*")
								     ->from("city")
								     ->queryAll(), 
								              CHtml::listData(Yii::app()->db->createCommand()
								      ->select("*")
								     ->from("city")
								     ->queryAll(), 
								              "name","name"
								         )); 
							?>
						
						<?php } else {?>
					
						<?php echo CHtml::activeLabel($model,'receiver_city'); ?>
						<?php echo CHtml::activeTextField($model,'receiver_city'); ?>	
					<?php } ?>
					</div>
					<div class="row">
						<?php echo CHtml::activeLabel($model,'user_address'); ?>
						<?php echo CHtml::activeTextField($model,'user_address'); ?>
					</div>
					
					
					
					<div class="row">
						<?php echo CHtml::activeLabel($model,'datetime_del'); ?>
						<?php echo CHtml::activeTextField($model,'datetime_del'); ?>
					</div>
					
					<div class="row">
						<?php echo CHtml::activeLabel($model,'user_comment'); ?>
						<?php echo CHtml::activeTextArea($model,'user_comment'); ?>
					</div>
					
					<div class="row">
						<?php echo CHtml::activeLabel($model,'card_text'); ?>
						<?php echo CHtml::activeTextArea($model,'card_text'); ?>
					</div>
					
					<div class="row">
						<?php echo CHtml::activeLabel($model,'doPhoto'); ?>
						<?php echo CHtml::activeCheckBox($model, 'doPhoto',array('checked'=>$model->doPhoto?"1":"0")); ?>
					</div>
					<div class="row">
						<?php echo CHtml::activeLabel($model,'do_card'); ?>
						<?php echo CHtml::activeCheckBox($model, 'do_card',array('checked'=>$model->do_card?"1":"0")); ?>
					</div>
					<?php if(!$model->isNewRecord): ?>
						<div class="row">
							<?php echo CHtml::activeLabel($model,'not_disturb'); ?>
							<?php echo CHtml::activeCheckBox($model, 'not_disturb',array('checked'=>$model->not_disturb?"1":"0")); ?>
						</div>
						<div class="row">
							<?php echo CHtml::activeLabel($model,'phone1'); ?>
							<?php echo CHtml::activeTextField($model,'phone1'); ?>
						</div>
						
						<div class="row">
							<?php echo CHtml::activeLabel($model,'phone2'); ?>
							<?php echo CHtml::activeTextField($model,'phone2'); ?>
						</div>
						<div class="row">
							<?php echo CHtml::activeLabel($model,'admin_comment'); ?>
							<?php echo CHtml::activeTextArea($model,'admin_comment'); ?>
							<div class="hint"><?php echo Yii::t('OrdersModule.admin', 'Этот текст не виден для пользователя.'); ?></div>
						</div>
					<?php endif;?>
				</div>
			</td>
			<td>
				<!-- Right block -->
				<?php if(!$model->isNewRecord): ?>
					<div style="float: right;padding-right: 10px">
						<a href="javascript:openAddProductDialog(<?php echo $model->id ?>);"><?php echo Yii::t('OrdersModule.admin','Добавить продукт') ?></a>
					</div>
					<div id="dialog-modal" style="display: none;" title="<?php echo Yii::t('OrdersModule.admin','Добавить продукт') ?>">
						<?php
						$this->renderPartial('_addProduct', array(
							'model'=>$model,
						));
						?>
					</div>

					<h4><?php echo Yii::t('OrdersModule.admin','Продукты') ?></h4>

					<div id="orderedProducts">
						<?php
						
						$this->renderPartial('_orderedProducts', array(
							'model'=>$model,
							'photos'=>$photos,
							'orderPhoto' => $orderPhoto,

						));
						?>
					</div>
				<?php endif;?>

			</td>
		</tr>
	</table>
	<?php echo CHtml::endForm(); ?>
</div>