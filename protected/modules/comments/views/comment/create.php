<input type="button" id="show_comments" class="btn-purple"  onclick="shw_comments()">
<div class="comments_form">

                <!-- col31 (begin) -->
                <div class="col31">
<?php
/**
 * @var $this Controller
 * @var $form CActiveForm
 */

// Load module
$module = Yii::app()->getModule('comments');
// Validate and save comment on post request
$comment = $module->processRequest($model);
// Load model comments
$comments = Comment::getObjectComments($model);

$currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// Display comments
if(!empty($comments))
{
	foreach($comments as $k=> $row)
	{
	?>
		<div class="comment" id="comment_<?php echo $row->id; ?>">
			<div class="b-comment">
                            <div class="com-body">
                                <p>
                                    <?php echo nl2br(CHtml::encode($row->text)); ?>
                                </p>
                                <?php
$this->widget('CStarRating',array(
            'name'=>'rating_'.$k,
            // 'class'=>'stars',
            'value'=>$row->rating,
            'readOnly'=>true,
            'minRating'=>1,
	            'maxRating'=>5,
	            'starCount'=>5,
            ));
?>

                            </div>
                            <div class="com-bottom">
                                <div class="name"><?php echo CHtml::encode($row->name); ?></div>
                                <div class="date"><?php echo $row->created; ?></div>
                            </div>
                        </div>
		</div>
	<?php
	}
}
?>
</div>

<div class="col32">
<div class="data-form" id="leave_comment">
	<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'                     =>'comment-create-form',
		'action'                 =>$currentUrl.'#comment-create-form',
		'enableAjaxValidation'   =>false,
		'enableClientValidation' =>true,
	)); ?>

	
	<b class="title"><?=Yii::t('CommentsModule.core','Leave your review of 7Roses')?></b>
                            <div class="text">
                                <p>
                                    <?=Yii::t('CommentsModule.core','Share your experiences with other people')?>
                                </p>
                            </div>
	<table cellpadding="5" border="0"><tr><td>
	<?php if(Yii::app()->user->isGuest): ?>
        <div class="s1">
				<?php echo $form->labelEx($comment,'your name'); ?></td><td>
				<?php echo $form->textField($comment,'name');?>
				<?php echo $form->error($comment,'name'); ?>
		</div>
		</td></tr>
		<tr><td>
		<div class="s1">

				<?php echo $form->labelEx($comment,'email'); ?></td><td>
				<?php echo $form->textField($comment,'email'); ?>
				<?php echo $form->error($comment,'email'); ?>
		</div>
		<?php else: echo"<td>"; ?>
	<?php endif; ?>
		</td></tr>
		<tr><td>
		<div class="s1">
			
				<?php echo $form->labelEx($comment,'city'); ?></td><td>
				<?php echo $form->textField($comment,'city'); ?>
				<?php echo $form->error($comment,'city'); ?>

		</div>
				</td></tr>
		<tr><td></td><td>
		<div class="s1">
		<?php echo $form->labelEx($comment,'rating'); ?>
		<br>
        <?php     
                $this->widget('CStarRating',array(
                          'model'=>$comment,
                          'attribute'=>'rating',
                          'minRating'=>1,
                          'maxRating'=>5,
                          'starCount'=>5,
						  'allowEmpty'=> true, 
                          'readOnly'=>false,
                        ));
						 echo '<br>'.$form->error($comment,'rating'); 
                 ?>
			</div>
			</td></tr>
			<tr><td>
        <?php echo $form->error($model,'star_rate'); ?>
		<div class="s1">
			
				<?php echo $form->labelEx($comment,'text'); ?></div></td><td>
				<div class="s1"><?php echo $form->textArea($comment,'text', array('rows'=>5, 'cols'=>25)); ?>
				<?php echo $form->error($comment,'text'); ?>
		
		</div>
		
</td></tr>
<tr><td>	<div class="s3">	
			<label><?php $this->widget('CCaptcha', array('clickableImage'=>true,'showRefreshButton'=>false)) ?></label></td><td>
			<?php echo $form->textField($comment,'verifyCode', array('required'=>true, 'placeholder'=>''.Yii::t('CommentsModule.core', 'Enter secret code').'')); ?>
		</div>
		
</td></tr>
</table>
		<div class="row buttons">
			<?php echo CHtml::submitButton(Yii::t('CommentsModule.core', 'Submit'),array('class'=>'btn-purple')); ?>
		</div>

	<?php $this->endWidget(); ?><!-- /form -->
	</div>
</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.comments_form').css('display','none');
	$('#show_comments').attr('value', '<?=Yii::t("StoreModule.core","Show comments")?>');
});
function shw_comments(){
	if($('.comments_form').css('display')=="none"){
			$('.comments_form').css('display','block');
			$('#show_comments').attr('value', '<?=Yii::t("StoreModule.core","Hide comments")?>');
	}
	else if($('.comments_form').css('display')=="block"){
		$('.comments_form').css('display','none');
		$('#show_comments').attr('value', '<?=Yii::t("StoreModule.core","Show comments")?>');
	}


}
</script>