	<?php
/**
 * Product view
 * @var StoreProduct $model
 * @var $this Controller
 */
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
// Set meta tags
$this->pageTitle = ($model->meta_title) ? $model->meta_title : $model->name;
$this->pageKeywords = $model->meta_keywords;
$this->pageDescription = $model->meta_description;

// Register main script
Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl.'/product.view.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl.'/product.view.configurations.js', CClientScript::POS_END);

// Create breadcrumbs

$lang= Yii::app()->language;
if($lang == 'ua')
    $lang = 'uk';

$langArray = SSystemLanguage::model()->findByAttributes(array('code'=>$lang));
 $categoryTrans=StoreCategoryTranslate::model()->findAllByAttributes(array('language_id'=>$langArray->id));
// Create breadcrumbs
$ancestors = $this->model->mainCategory()->excludeRoot()->ancestors()->findAll();

foreach($ancestors as $c){
    foreach($categoryTrans as $ct){
        if($ct->object_id==$c->id)
	   $this->breadcrumbs[$ct->name] = $c->getViewUrl();
    }
}

// get Main parent category for full breadcrumbs path
$parent_sql = 'select `spcr`.`category`, `sc`.`full_path`, `sct`.`name`
  from `StoreProductCategoryRef` `spcr`
  left join `StoreCategory` `sc` on `sc`.`id` = `spcr`.`category`
  left join `StoreCategoryTranslate` `sct` on `sct`.`object_id` = `spcr`.`category`
  where `spcr`.`product` = ' . (int)$model->id . '
    and `spcr`.`is_main` = 1
    and `sct`.`language_id` = ' . (int)$langArray->id . '
  limit 1';
$parent_command = Yii::app()->db->createCommand($parent_sql);
$parent = $parent_command->queryRow();

if(!in_array('/' . $parent['full_path'], $this->breadcrumbs)){
    $this->breadcrumbs[$parent['name']] = '/' . $parent['full_path'];
}

$this->breadcrumbs[] = $model->name;



	$this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link(Yii::t('main','Home page'), array('/store/index/index')),
		'links'=>$this->breadcrumbs,
	));



// Fancybox ext
$this->widget('application.extensions.fancybox.EFancyBox', array(
	'target'=>'a.thumbnail',
));

?>


<div class="g-clearfix">
	
	<?php //$this->renderFile(Yii::getPathOfAlias('pages.views.pages.left_sidebar').'.php', array('popup'=>'city-product')); ?>
	
	<!-- products (begin) -->
	<div class="products">
	
	    <!-- h-pp (begin) -->
	    <div class="h-pp">
	        <div class="g-clearfix">
	            <div class="pp-left">
	                <?php
					// Main product image
					if($model->mainImage)
						echo CHtml::link(CHtml::image($model->mainImage->getUrl('373x373', 'resize'), $model->mainImage->title), $model->mainImage->getUrl(), array('class'=>'thumbnail'));
					else
						echo CHtml::link(CHtml::image('http://placehold.it/340x250'), '#', array('class'=>'thumbnail'));
					?>
                    <?php if($model->short_description): ?>
                    <div class="number g-clearfix">
                        <div class="sort sort-size" style="float: left; margin-left: 80px;">
                            <a class="drop-link" href="#" title=""><?=Yii::t('StoreModule.core','The composition and size')?></a>
                            <div class="sort-popup hidden">
                                <?=$model->short_description?>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
	            </div>
	            <div class="pp-right">
	                <?php echo CHtml::form(array('/orders/cart/add'))?>
	                    <h1 class="page-title"><?php echo CHtml::encode($model->name); ?></h1>
	                    <div class="article"><?=Yii::t('StoreModule.core','Vendor code:')?> <span><?=$model->id?></span></div>
	                    
	                    <?php
	                    if($model->getEavAttributes())
						{
							$this->widget('application.modules.store.widgets.SAttributesTableRenderer', array(
								'model'=>$model,
								'htmlOptions'=>array(
									'class'=>'attributes'
								),
							));

						}
						
	                    ?>
	                    
	                    <?php if($model->type_id == 1):?>
	                    	<?php $this->renderPartial('_configurations', array('model'=>$model)); ?>
	                    <?php endif;?>
	                    
	                    
	                    <div class="pp-price">
	                        <div class="currency">
	                            <?=Yii::t('StoreModule.core','Show prices in')?>
	                            <select name="currency" id="selectCurrencyProduct">
	                            	<?php foreach(Yii::app()->currency->currencies as $currency):?>
									<option value="<?=$currency->id?>" <?=(Yii::app()->currency->active->id===$currency->id) ? "selected='selected'" : ""?>><?=$currency->symbol?></option>		
									<?php endforeach;?>
	                            </select>
	                        </div>
	                        
	                        <div class="price">
								<span id="productPrice"><?php echo StoreProduct::formatPrice($model->toCurrentCurrency()); ?></span>
								<?php echo Yii::app()->currency->active->symbol; ?>
							</div>
	                        
	                    </div>
	                    <div class="pp-reg">
	                        <div class="sort sort-reg">
	                            
	                            <?php $this->renderFile(Yii::getPathOfAlias('pages.views.pages.popup_regions').'.php'); ?>
	                            
	                        </div>
	                        <p><?=Yii::t('StoreModule.core','Assortment depends on the city')?></p>
	                    </div>
	                    
	                    <?php                       
							echo CHtml::hiddenField('product_id', $model->id);
							echo CHtml::hiddenField('product_price', $model->price);
							echo CHtml::hiddenField('use_configurations', $model->use_configurations);
							echo CHtml::hiddenField('currency_rate', Yii::app()->currency->active->rate);
							echo CHtml::hiddenField('configurable_id', 0);
							echo CHtml::hiddenField('quantity', 1);
			
							if($model->isAvailable)
							{
								echo CHtml::ajaxSubmitButton(Yii::t('StoreModule.core','Order'), array('/orders/cart/add'), array(
									'dataType' => 'json',
									'type'=>'post',
									'success'  => 'js:function(data, textStatus, jqXHR){processCartResponse(data, textStatus, jqXHR)}',
								), array(
									'id'=>'buyButton',
									'class'=>'btn-purple'
								));
							}
							else
							{
								echo CHtml::link(Yii::t('StoreModule.core','Report appearance'), '#', array(
									'onclick' => 'showNotifierPopup('.$model->id.'); return false;',
								));
							}
						?>
	                    <span id="phoneOrder" class="btn-green call-back-order"><?=Yii::t('StoreModule.core','Order by phone')?></span>
	                    <span class="link-del-way"><?=Yii::t('StoreModule.core','Payment methods')?></span>

	               <?php echo CHtml::endForm();?>
	            </div>
	        </div>
	        <!-- b-page-text (begin) -->
	        <div class="b-page-text text ">
	            <p>
	                <?php echo $model->full_description; ?>
	            </p>
	        </div>
	        <!-- b-page-text (end) -->
	    </div>
	    <!-- h-pp (end) -->
	
	    <!-- b-last-photos (begin) -->
	    <div class="b-last-photos">
	    	<?php if(isset($photos) && !empty($photos)){ ?>
	        <h3 class="title"><?=Yii::t('StoreModule.core','The last photo of the bouquet deliveries')?></h3>
	        <a href="/product/photos/id/<?=$model->id?>" title=""><?=Yii::t('StoreModule.core','All photos')?></a>
	        <div class="g-clearfix">
	        	
		        	<?php foreach ($photos as $key => $value) { ?>
		            <div class="b-photo">
		                <div class="visual">
		                    <div class="img">
		                        <img src="<?php echo '/uploads/'.$value->photo;?>" alt=""/>
		                    </div>
		                </div>
		                <!-- <div class="title">г. Одесса</div> -->
		            </div>
		            <?php } ?>
	             <?php } ?>
	        </div>
	    </div>
	    <!-- b-last-photos (end) -->
<!-- 	</div> -->
	<!-- products (end) -->
<?php $this->renderPartial('comments.views.comment.create', array(
       	 'model'=>$model, // Commentable model instance
    	));?>
</div>





<div class="hidden" >
    <!-- modal (begin) -->
    <div id="call-back-modal" class="box-modal call-back-modal">
        <div class="title"><?=Yii::t('StoreModule.core','Order by phone')?></div>
       
            <input class="orderName" type="text" placeholder="<?=Yii::t('StoreModule.core','Name')?>" required="required" />
            <input class="orderEmail" type="email" placeholder="E-mail" required="required" />
            <input class="orderPhone" type="text" placeholder="<?=Yii::t('StoreModule.core','Phone')?>" required="required" />
            <input id="submit_button" class="btn-purple" type="submit" value="<?=Yii::t('StoreModule.core','Submit')?>" />
        </div>
    
    <!-- modal (end) -->
    <!-- modal (begin) -->
    <div id="payment-modal" class="box-modal payment-modal">
        <?=Yii::t('StoreModule.core',"<div class = 'title'> Payment methods </div>
        <div class = 'content-text'>
            <p>
                <strong> cash </strong>
            </p>
            <p>
                Cash payment is made in advance before the fact of delivery of cash to the courier.
            </p>
            <p>
                The cost of courier services Departure is 20 UAH. <br/>
                Western Union is very convenient way to transfer money for those who do not live in Ukraine. If you, for example, you live in America and your relatives or friends in Ukraine and you want to order our services, this is the fastest and most convenient way. Send money by Western Union is possible from 170 000 places all over the world.
            </p>
            <p>
                After the transfer inform us of the fact of payment. To speed up the execution of the order, send a scanned paid receipt by e-mail or fax. Otherwise, your order will be sent for execution only upon receipt of money (2-3 banking days)
            </p>

            <p> <strong> Payment card Visa / MasterCard </strong> </p>
            <p>
                In our shop you can pay by means of payment (plastic) card. <br/>
                For safety Portmone.com fulfills international standard Payment Card Industry Data Security Standard (PCI DSS).
            </p>
            <p>
                Rortmone.com the first company in Ukraine, which has successfully passed an international safety audit for compliance with the standard Payment Card Industry Data Security Standard (PCI DSS), and has received the certificate №499938160100203, issued by the German company SRC (Security Research and Consulting GmbH). SRC - an independent auditing company conducting the certification for compliance with all safety requirements (MasterCard Site Data Protection and VISA Account Information Security), established by the major payment systems VISA and MasterCard.
            </p>
        </div>")?>
    </div>
    <!-- modal (end) -->
    
</div>

<script type="text/javascript">
$(document).ready(function(){
	
	var qty = $(".attributes td:eq(1)").text()+" роз";
	var position = $(".variantData option:contains('"+qty+"')").val()
	
	if(position){
		$(".variantData").val(position);
	}
	
});
var csrf='<?=Yii::app()->request->csrfToken?>';
var id='<?=$model->id?>';
var quantity=$('.number option:selected').text();
$('#submit_button').click(function(){
	$.ajax({
		type:'post',
		dataType:'json',
		data:{'YII_CSRF_TOKEN':csrf ,'email':$('.orderEmail').val(),'name':$('.orderName').val(),'phone':$('.orderPhone').val(),'id':id,'quantity':quantity},
		url:'/orders/cart/phone',
		success:function(ev){console.log(ev);},
		error:function(er){console.log(er);}
	});

});
//
</script>