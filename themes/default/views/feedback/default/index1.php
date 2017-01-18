<?php

/**
 * @var $this Controller
 */

$this->pageTitle = Yii::t('FeedbackModule.core', 'Feedback');

?>
<ul class="breadcrumbs">
        <li><a href="/"/><?=Yii::t('main','Home page')?></a></li>
        <li>&nbsp;/&nbsp;</li>
        <li><?=Yii::t('FeedbackModule.core','Feedback')?></li>
    </ul>
<h1 class="has_background"><?php echo Yii::t('FeedbackModule.core', 'Feedback') ?></h1>


<div class="data-form">
<?php $form=$this->beginWidget('CActiveForm'); ?>

		<!-- Display errors  -->
		<?php echo $form->errorSummary($model); ?>

		<div class="s3">
			<?php echo CHtml::activeLabel($model,Yii::t('FeedbackModule.core','Name'),array('required'=>true)); ?>
			<?php echo CHtml::activeTextField($model,'name', array('required'=>true, 'placeholder'=>''.Yii::t('FeedbackModule.core', 'Your name').'')); ?>
		</div>

		<div class="s3">
			<?php echo CHtml::activeLabel($model,Yii::t('FeedbackModule.core','Email'), array('required'=>true)); ?>
			<?php echo CHtml::activeTextField($model,'email', array('required'=>true, 'placeholder'=>''.Yii::t('FeedbackModule.core', 'Email').'')); ?>
		</div>

		<div class="s3">
			<?php echo CHtml::activeLabel($model,Yii::t('FeedbackModule.core','Message'), array('required'=>true)); ?>
			<?php echo CHtml::activeTextArea($model,'message', array('rows'=>15, 'required'=>true, 'placeholder'=>''.Yii::t('FeedbackModule.core', 'Message').'')); ?>
		</div>

		<?php if(Yii::app()->settings->get('feedback', 'enable_captcha')): ?>
		<div class="s1">
			<label><?php $this->widget('CCaptcha', array('clickableImage'=>true,'showRefreshButton'=>false)) ?></label>
			<?php echo CHtml::activeTextField($model, 'code')?>
		</div>
		<?php endif; ?>

		<div class="row buttons">
			<button type="submit" class="btn-purple"><?php echo Yii::t('FeedbackModule.core', 'Submit') ?></button>
		</div>
	</fieldset>
<?php $this->endWidget(); ?>
</div>