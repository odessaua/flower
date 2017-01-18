<?php
/**
 * Remind user password view
 */

$this->pageTitle = Yii::t('main','Remind password');
?>

<h1 class="has_background"><?php echo Yii::t('main','Remind password'); ?></h1>

<div class="login_box rc5">
	<div class="data-form">
	<?php echo Yii::t('UsersModule.core','<p>Enter your e-mail address to receive your password</p>'); ?>
		<?php
		echo CHtml::form();
		echo CHtml::errorSummary($model);
		?>

		<div class="s1">
			<?php echo CHtml::activeLabel($model,'email', array('required'=>true)); ?>
			<?php echo CHtml::activeTextField($model,'email'); ?>
		</div>

		<div class="s1">
			<?php echo CHtml::submitButton(Yii::t('UsersModule.core', 'Remind'),array('class'=>'btn-purple')); ?>
		</div>

		<div class="row buttons">
			<?php echo CHtml::link(Yii::t('UsersModule.core', 'Registration'), array('register/register')) ?><br>
			
		</div>
		<?php echo CHtml::endForm(); ?>
	</div>
</div>
