<?php $slider=SSystemSlider::model()->findAll();
// var_dump($slider);
?>
<div class="g-clearfix">
	<!-- col-1 (begin) -->
	<div class="col-1">
	    <div class="slider">
	        <div id="slider">
	            <ul>
                        <?php foreach ($slider as $one) { ?>
                            <li>
                                <a href="<?=$one['url']?>" title="<?=$one['url']?>">
                                    <img width="812" height="282" src="<?= 'uploads/slider/'.$one['photo'] ?>" alt=""/>
                                </a>
                            </li>
                        <?php } ?>
	            </ul>
	        </div>
	    </div>
	
	    <?php $this->renderFile(Yii::getPathOfAlias('pages.views.pages.left_sidebar').'.php'); ?>
	
	    <!-- col-12 (begin) -->
	    <div class="col-12">
	
	        <!-- products (begin) -->
	        <div class="products g-clearfix">
	        	<?php
					foreach($popular as $p)
						$this->renderPartial('_product', array('data'=>$p));
				?>
	        </div>
	        <!-- products (end) -->
	
	        <!-- b-page-text (begin) -->
	        <div class="b-page-text text ">
	            <?=$mainContent->full_description?>
	        </div>
	        <!-- b-page-text (end) -->
	    </div>
	    <!-- col-12 (end) -->
	</div>
	<!-- col-1 (end) -->
	
	<!-- col-22 (begin) -->
        <?php $baner=  SSystemBaner::model()->findAll();?>
	<div class="col-22">
	    <div class="action">
	        <a href="#" title="">
	            <img width="218" heigth="282" src="<?='uploads/baners/'.$baner[2]['photo']?>" alt="" />
	        </a>
	    </div>

	    <!-- b-comments (begin) -->
	    <div class="b-comments">
	        <h3 class="title"><?=Yii::t('main','Customer Reviews')?></h3>
	        <ul>
	        	<?php foreach ($comments as $key => $value): ?>
	            <li>
	            	
	                <div class="visual">
	                    <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/avatar01.jpg" alt=""/>
	                </div>
	                <div class="info">
	                    <div class="name"><?=$value['name']?></div>
	                    <p>
	                        <?=$value['text']?>
	                    </p>
	                </div>
	            
	            </li>
	            <?php endforeach;?>
	        </ul>
	    </div>
	    <!-- b-comments (end) -->
	
	    <!-- b-socials (begin) -->
	    <div class="b-socials">
	        <h3 class="title"><?=Yii::t('main','We are in social networks')?></h3>
	        <div>
	            <a class="fb" href="#" title="Facebook"></a>
	            <a class="go" href="#" title="Google+"></a>
	            <a class="ok" href="#" title="Одноклассники"></a>
	            <a class="vk" href="#" title="ВКонтакте"></a>
	        </div>
	    </div>
	    <!-- b-socials (end) -->
	</div>
	<!-- col-22 (end) -->
</div>