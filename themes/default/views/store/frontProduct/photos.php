<?php
/**
 * Product view
 * @var StoreProduct $model
 * @var $this Controller
 */

// Set meta tags
// $this->pageTitle = ($model->meta_title) ? $model->meta_title : $model->name;
// $this->pageKeywords = $model->meta_keywords;
// $this->pageDescription = $model->meta_description;

// Register main script
Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl.'/product.view.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl.'/product.view.configurations.js', CClientScript::POS_END);

// Create breadcrumbs
// if($model->mainCategory){
// 	$ancestors = $model->mainCategory->excludeRoot()->ancestors()->findAll();

// 	foreach($ancestors as $c)
// 		$this->breadcrumbs[$c->name] = $c->getViewUrl();

// 	// Do not add root category to breadcrumbs
// 	if ($model->mainCategory->id != 1)
// 		$this->breadcrumbs[$model->mainCategory->name] = $model->mainCategory->getViewUrl();
// }

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
	          
	            
	        </div>
	       
	    <div class="b-last-photos">
	        <h3 class="title"><?=Yii::t('StoreModule.core','The last photo of the bouquet deliveries')?></h3>
	        <!-- <a href="#" title="">Все фото доставок</a> -->
	        <div class="g-clearfix">
	        	<?php if(isset($allPhotos)){?>
		        	<?php foreach ($allPhotos as $key => $value) { ?>
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
	</div>
	<!-- products (end) -->

</div>

<script type="text/javascript">
$(document).ready(function(){
	
	var qty = $(".attributes td:eq(1)").text()+" роз";
	var position = $(".variantData option:contains('"+qty+"')").val()
	
	if(position){
		$(".variantData").val(position);
	}
	
});
</script>