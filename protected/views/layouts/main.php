<?php
//var_dump($this->uniqueid, $this->action->Id);
	Yii::import('application.modules.users.forms.UserLoginForm');
	Yii::import('application.modules.store.models.StoreCategory');

	$assetsManager = Yii::app()->clientScript;
	$assetsManager->registerCoreScript('jquery');
	$assetsManager->registerCoreScript('jquery.ui');

	// jGrowl notifications
	Yii::import('ext.jgrowl.Jgrowl');
	Jgrowl::register();

$meta_page_title = CHtml::encode($this->pageTitle);
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $meta_page_title; ?></title>
	<meta charset="UTF-8"/>
    <meta name="title" content="<?php echo $meta_page_title; ?>">
	<meta name="description" content="<?php echo CHtml::encode($this->pageDescription) ?>">
	<meta name="keywords" content="<?php echo CHtml::encode($this->pageKeywords) ?>">
	
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <!--[if lte IE 8]>
    <link href='http://fonts.googleapis.com/css?family=PT+Sans:400&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=PT+Sans:700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=PT+Sans:400italic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <![endif]-->
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/assets/css/style.css"/>
    <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/assets/js/common.js"></script>
    <link rel="shortcut icon" href="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/favicon.ico" type="image/ico" />
</head>
<body>

<div class="wrapper">

    <!-- header-top (begin) -->
    <div class="header-top">
        <div class="wrap g-clearfix">
            <div class="lang">
            	<?php 
                    $this->widget('application.components.widgets.LanguageSelector');
                ?>
                
            </div>

            
            <!-- region-popup (begin) -->
            <div class="sort sort-reg del-reg">
                
                <?php $this->renderFile(Yii::getPathOfAlias('pages.views.pages.popup_regions').'.php', array('popup'=>'city-header')); ?>
                
            </div>
            <!-- region-popup (end) -->
            <div class="sort cabinet-enter">
            	
            	<?php if(Yii::app()->user->isGuest): ?>
            	
                <span class="drop-link link-cabinet-enter"><span><?=Yii::t('main',"My Account")?></span></span>
                
                <div class="sort-popup auth hidden">
                    <a href="/users/register"><?= Yii::t('main','Not registered')?>?</a>
                    <b><?=Yii::t('main','Auth')?></b>
                    
                    <?php 
                    $model = new UserLoginForm;
                    $form=$this->beginWidget('CActiveForm', array(
						'id'=>'user-login-form',
						'action'=>'/users/login',
						'enableAjaxValidation'=>true,
						'clientOptions'=>array(
					      'validateOnSubmit'=>true,
					     ),
					)); ?>
                        <div class="userdata">
                        	<?php echo $form->textField($model,'username', array('placeholder'=>Yii::t('main',"Login"))); ?>
                        	<?php echo $form->error($model,'username'); ?>
                        </div>
                        <div class="userdata">
                            <?php echo $form->passwordField($model,'password',array('placeholder'=>Yii::t('main',"Password"))); ?>
                            <?php echo $form->error($model,'password'); ?>
                        </div>
                        <div class="permanent">
                            <a href="/users/remind"><?= Yii::t('main','Send password')?></a>
                            <?php echo CHtml::activeCheckBox($model,'rememberMe', array('id'=>'to-remember')); ?>
                            <label for="to-remember"><?=Yii::t('main',"Remember me")?></label>
                        </div>
                        <input class="btn-purple enter-btn" type="submit" value="Войти" />
                    <?php $this->endWidget(); ?>
                </div>
                
                <?php else:?>
                	<span class="drop-link link-cabinet-enter profileLink" onclick="location.href='/users/profile/orders'"><span><?=Yii::t('main',"My orders")?></span></span>
                	<span class="drop-link link-cabinet-enter profileLink" onclick="location.href='/users/profile'"><span><?=Yii::t('main',"My Account")?></span></span>
                	<span class="drop-link link-cabinet-enter profileLink" onclick="location.href='/users/logout'"><span><?=Yii::t('main',"Exit")?></span></span>
                <?php endif;?>
                
            </div>
            <ul class="menu">
				<li><a title="" href="/page/payment.html"><?=Yii::t('main',"Payment")?></a></li>
                <li><a title="" href="/page/delivery.html"><?=Yii::t('main',"Delivery Terms")?></a></li>
                <li><a title="" href="/page/garanty.html"><?=Yii::t('main',"Garanty")?></a></li>
                <li><a title="" href="/feedback"><?=Yii::t("main", "Contacts")?></a></li>
            </ul>
            
        </div>
    </div>
    <!-- header-top (end) -->

    <!-- header (begin) -->
    <div class="header ">
        <div class="wrap">
            <a href="/cart" title="" class="b-cart" id="cart">
            	<?php $this->renderFile(Yii::getPathOfAlias('orders.views.cart._small_cart').'.php'); ?> 
            </a>
            <span class="btn-search"><span><?=Yii::t('main','Search')?></span></span>
            
            <ul>
                <li>
                    <a href='/' class="logo" title="7roses">
                        <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/logo_<?=Yii::app()->language?>.png" alt="7roses" />
                    </a>
                </li>
                <li class="contact phones">
                    <div><b>+38 067 480 6525</b></div>
                    <div><b>+38 048 716 5465</b></div>
                </li>
                <li class="contact currency">
                    <div class="b-currency">
                    <h2 class="title"><?=Yii::t('main','Currency')?></h2>
            <?php
            foreach(Yii::app()->currency->currencies as $currency)
                {
                    echo CHtml::ajaxLink($currency->symbol, '/store/ajax/activateCurrency/'.$currency->id, array(
                        'success'=>'js:function(){window.location.reload(true)}',
                    ),array('id'=>'sw'.$currency->id,'class'=>Yii::app()->currency->active->id===$currency->id?'active':''));
                }
            ?>
             </div>
                </li>
            </ul>
        </div>

        <!-- search-popup (begin) -->
        <div class="header-popup search-popup">
            <span class="popup-close"></span>
            <div class="search-form">
                <span><?=Yii::t('main','Site search')?></span>
                <?php echo CHtml::form($this->createUrl('/store/category/search')) ?>
	                <input class="search-field" type="text" placeholder="<?=Yii::t('main','Use keywords to find')?>" name="q" id="searchQuery">
	                <input class="btn-purple" type="submit" value="<?=Yii::t('main','Search')?>">
                <?php echo CHtml::endForm() ?>
            </div>
        </div>

        <!-- search-popup (end) -->
    </div>
    <!-- header (end) -->
<?php 
     $lang= Yii::app()->language;
                    if($lang == 'ua')
                        $lang = 'uk';

                    $langArray = SSystemLanguage::model()->findByAttributes(array('code'=>$lang));
?>
    <!-- page-content (begin) -->
    <div class="page-content wrap">

        <!-- nav (begin) -->
        <ul class="nav g-clearfix">
            <li>
                <?php $product = StoreCategory::model()->findByPk(230);
                       $tansProduct = StoreCategoryTranslate::model()->findByAttributes(array('object_id'=>'230', 'language_id'=>$langArray->id));
                ?>
                <a title="" href="/<?=$product['url']?>">
                    <div class="visual">
                        <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/nav01.png" alt=""/>
                    </div>
                    <div class="title"><?php echo $tansProduct->name; ?></div>
                </a>
             <ul>
              <?php
                   

                    $items = StoreCategory::model()->findByPk(230)->asCMenuArray();
                     foreach($items['items'] as $item):
                        $tansItem = StoreCategoryTranslate::model()->findByAttributes(array('object_id'=>$item['url']['id'], 'language_id'=>$langArray->id));
              ?>
                 <li><a href="/<?=$item['url']['url']?>"><?=$tansItem->name;?></a></li>
                 <?php endforeach;?>
                </ul>  
            </li>
            <li>
                <?php $product = StoreCategory::model()->findByPk(234);
                       $tansProduct = StoreCategoryTranslate::model()->findByAttributes(array('object_id'=>'234', 'language_id'=>$langArray->id));
                ?>
                <a title="" href="/<?=$product['url']?>">
                    <div class="visual">
                        <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/nav02.png" alt=""/>
                    </div>
                    <div class="title"><?php echo $tansProduct->name; ?></div>
                </a>
             <ul>
              <?php
                   

                    $items = StoreCategory::model()->findByPk(234)->asCMenuArray();
                     foreach($items['items'] as $item):
                        $tansItem = StoreCategoryTranslate::model()->findByAttributes(array('object_id'=>$item['url']['id'], 'language_id'=>$langArray->id));
              ?>
                 <li><a href="/<?=$item['url']['url']?>"><?=$tansItem->name;?></a></li>
                 <?php endforeach;?>
                </ul>  
            </li>
            <li>
                <?php $product = StoreCategory::model()->findByPk(232);
                       $tansProduct = StoreCategoryTranslate::model()->findByAttributes(array('object_id'=>'232', 'language_id'=>$langArray->id));
                ?>
                <a title="" href="/<?=$product['url']?>">
                    <div class="visual">
                        <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/nav03.png" alt=""/>
                    </div>
                    <div class="title"><?php echo $tansProduct->name; ?></div>
                </a>
             <ul>
              <?php
                   

                    $items = StoreCategory::model()->findByPk(232)->asCMenuArray();
                     foreach($items['items'] as $item):
                        $tansItem = StoreCategoryTranslate::model()->findByAttributes(array('object_id'=>$item['url']['id'], 'language_id'=>$langArray->id));

              ?>
                 <li><a href="/<?=$item['url']['url']?>"><?=$tansItem->name;?></a></li>
                 <?php endforeach;?>
                </ul>  
            </li>
            <li>
                <?php $product = StoreCategory::model()->findByPk(235);
                       $tansProduct = StoreCategoryTranslate::model()->findByAttributes(array('object_id'=>'235', 'language_id'=>$langArray->id));
                ?>
                <a title="" href="/<?=$product['url']?>">
                    <div class="visual">
                        <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/nav04.png" alt=""/>
                    </div>
                    <div class="title"><?php echo $tansProduct->name; ?></div>
                </a>
             <ul>
              <?php
                   

                    $items = StoreCategory::model()->findByPk(235)->asCMenuArray();
                     foreach($items['items'] as $item):
                        $tansItem = StoreCategoryTranslate::model()->findByAttributes(array('object_id'=>$item['url']['id'], 'language_id'=>$langArray->id));
              ?>
                 <li><a href="/<?=$item['url']['url']?>"><?=$tansItem->name;?></a></li>
                 <?php endforeach;?>
                </ul>  
            </li>
           <li>
                <?php $product = StoreCategory::model()->findByPk(236);
                       $tansProduct = StoreCategoryTranslate::model()->findByAttributes(array('object_id'=>'236', 'language_id'=>$langArray->id));
                ?>
                <a title="" href="/<?=$product['url']?>">
                    <div class="visual">
                        <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/nav05.png" alt=""/>
                    </div>
                    <div class="title"><?php echo $tansProduct->name; ?></div>
                </a>
             <ul>
              <?php
                   

                    $items = StoreCategory::model()->findByPk(236)->asCMenuArray();
                     foreach($items['items'] as $item):
                        $tansItem = StoreCategoryTranslate::model()->findByAttributes(array('object_id'=>$item['url']['id'], 'language_id'=>$langArray->id));
              ?>
                 <li><a href="/<?=$item['url']['url']?>"><?=$tansItem->name;?></a></li>
                 <?php endforeach;?>
                </ul>  
            </li>
            <li>
                <?php $product = StoreCategory::model()->findByPk(237);
                       $tansProduct = StoreCategoryTranslate::model()->findByAttributes(array('object_id'=>'237', 'language_id'=>$langArray->id));
                ?>
                <a title="" href="/<?=$product['url']?>">
                    <div class="visual">
                        <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/nav06.png" alt=""/>
                    </div>
                    <div class="title"><?php echo $tansProduct->name; ?></div>
                </a>
             <ul>
              <?php
                   

                    $items = StoreCategory::model()->findByPk(237)->asCMenuArray();
                     foreach($items['items'] as $item):
                        $tansItem = StoreCategoryTranslate::model()->findByAttributes(array('object_id'=>$item['url']['id'], 'language_id'=>$langArray->id));
              ?>
                 <li><a href="/<?=$item['url']['url']?>"><?=$tansItem->name;?></a></li>
                 <?php endforeach;?>
                </ul>  
            </li>
            <li>
                <?php $product = StoreCategory::model()->findByPk(276);
                       $tansProduct = StoreCategoryTranslate::model()->findByAttributes(array('object_id'=>'276', 'language_id'=>$langArray->id));
                ?>
                <a title="" href="/<?=$product['url']?>">
                    <div class="visual">
                        <img src="<?php echo Yii::app()->theme->baseUrl ?>/assets/img/nav07.png" alt=""/>
                    </div>
                    <div class="title"><?php echo $tansProduct->name; ?></div>
                </a>
            </li>
        </ul>

        <!-- nav (end) -->
        
		<?php if(($messages = Yii::app()->user->getFlash('messages'))): ?>
			<div class="flash_messages">
				<button class="close">×</button>
				<?php
					if(is_array($messages))
						echo implode('<br>', $messages);
					else
						echo $messages;
				?>
			</div>
		<?php endif; ?>
	
		<?php echo $content; ?>

    </div>
	 <!-- page-content (begin) -->

    <div class="gag"></div>
</div>

<!-- footer (begin) -->
<div class="footer">
    <div class="wrap">
        <div class="copyright">
            <p>&copy; 7Roses 2014 - <?= date('Y')?></p>
            <p><?=Yii::t('main','All rights reserved')?></p>
        </div>
        <div class="qmi">
            <style>
                .oocab-column{
                    width: 300px;
                    height: auto;
                    float: left;
                    margin-left: 30px;
                }
                .oocab-column-item{
                    width: 100%;
                    height: auto;
                    padding: 0 0 15px;
                }
            </style>
            <div class="oocab-column">
                <?php
                if(!empty($this->layout_params['city_address']) && !empty($this->layout_params['city_id'])){
                    Yii::import('application.modules.store.models.CityTranslate');
                    $cr = new CDbCriteria();
                    $cr->condition = "`language_id` = " . (int)$this->language_info->id . "
                    AND `object_id` = " . (int)$this->layout_params['city_id'] . "
                    AND (`firm_name` != '' AND `firm_name` IS NOT NULL)";
                    $ca_item = CityTranslate::model()->find($cr);
                }
                if(empty($ca_item)){
                    $ca_item = new stdClass();
                    $ca_item->name = Yii::t('main','Odessa');
                    $ca_item->firm_name = '7Roses';
                    $ca_item->firm_address = Yii::t('main','Deribasovskaya 12');
                    $ca_item->firm_phone = '+38 048 716 54 65';
                }
                ?>
                <div class="oocab-column-item">
                    <div class="ocabci-row"><?= $ca_item->name; ?>, <?= Yii::t('main','Ukraine'); ?></div>
                    <div class="ocabci-row"><?= Yii::t('main','Title'); ?>: <?= $ca_item->firm_name; ?></div>
                    <div class="ocabci-row"><?= Yii::t('main','Address'); ?>: <?= $ca_item->firm_address; ?></div>
                    <div class="ocabci-row"><?= Yii::t('main','Phone'); ?>: <?= $ca_item->firm_phone; ?></div>
                </div>
            </div>
        </div>
        
        <!-- menu-bottom (begin) -->
        <ul class="menu-bottom">
            <li>
                <ul>
                    <li><a title="" href="/flowers"><?=Yii::t('main','')?></a></li>
                    <li><a title="" href="/composition"><?=Yii::t('main','Compositions')?></a></li>
                    <li><a title="" href="/gifts"><?=Yii::t('main','Gifts')?></a></li>
                    <li><a title="" href="/sweets"><?=Yii::t('main','Sweets')?></a></li>
                    <li><a title="" href="/reason"><?=Yii::t('main','Reason')?></a></li>
                </ul>
            </li>
            <li>
                <ul>
					<li><a title="" href="/page/payment.html"><?=Yii::t('main','Payment')?></a></li>
                    <li><a title="" href="/page/delivery.html"><?=Yii::t('main','Delivery Terms')?></a></li>
                    <li><a title="" href="/page/garanty.html"><?=Yii::t('main','Garanty')?></a></li>
					<li><a title="" href="/page/faq.html"><?=Yii::t('main','FAQ')?></a></li>
                    <li><a title="" href="/feedback"><?=Yii::t('main','Contacts')?></a></li>
                </ul>
            </li>
        </ul>
        <!-- menu-bottom (end) -->
    </div>
</div>
<!-- footer (end) -->


<div class="hidden">
	
	<!-- modal (begin) -->
	<div id="cart-modal" class="box-modal cart-modal">
		
		<div class="added" id="popup-cart">
	   		<?php $this->renderFile(Yii::getPathOfAlias('orders.views.cart._popup_cart').'.php'); ?>
	   	</div>
	    
	    <div class="reg">
		    <div class="reg-title"><b><?=Yii::t('main','Delivery')?>:</b></div>
		    <div class="reg-sorts">
		        <div class="sort sort-reg">
		            <?php $this->renderFile(Yii::getPathOfAlias('pages.views.pages.popup_regions').'.php', array('popup'=>'city-popup')); ?>
		        </div>
		    </div>
		</div>
		<span class="btn-purple arcticmodal-close"><?=Yii::t('main','Continue shopping')?></span>
		<a class="btn-green" href="/cart"><?=Yii::t('main','Checkout')?></a>
	</div>
	<!-- modal (end) -->
	
	
	<!-- modal (begin) -->
	<div id="notavailable-modal" class="box-modal cart-modal">
		<?php
		$cityName = Yii::t('main','Kyiv');
		if(isset(Yii::app()->session['_city']))
			$cityName = Yii::app()->session['_city'];
		?>
		<span style="font-size:18px; font-weight:bold; display: block; padding:10px; text-align:center;"><?=Yii::t('main','This product is not available for the region')?>:<?=$cityName?></span><br/>
		
		<span class="btn-purple arcticmodal-close"><?=Yii::t('main','Continue shopping')?></span>
	</div>
	<!-- modal (end) -->
	
</div>
<script>

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){

  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),

  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)

  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

 

  ga('create', 'UA-92420651-1', 'auto');

  ga('send', 'pageview');

 

</script>

<script src="<?php echo Yii::app()->theme->baseUrl ?>/assets/js/jquery.arcticmodal-0.3.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl ?>/assets/js/main.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/assets/js/jquery.hoverIntent.minified.js"></script> 



<?php /*script type="text/javascript">
$(document).ready(function(){
	$(".regions ul li a").click(function(e){
		e.preventDefault();
		var city = $(this).text();

		$.ajax({
			type: "GET",
			url: "/site/changeCity",
			data: {city : city},
			success: function(data){
			    $(".cityName").text(data);
			    $(".sort-popup").addClass('hidden');
			    location.reload();
			}
		})
	})
});
</script*/?>

</body>
</html>