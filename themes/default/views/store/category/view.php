<?php

/**
 * Category view
 * @var $this CategoryController
 * @var $model StoreCategory
 * @var $provider CActiveDataProvider
 * @var $categoryAttributes
 */

// Set meta tags
$this->pageTitle = ($this->model->meta_title) ? $this->model->meta_title : $this->model->name;
$this->pageKeywords = $this->model->meta_keywords;
$this->pageDescription = $this->model->meta_description;
$lang= Yii::app()->language;
if($lang == 'ua')
    $lang = 'uk';

$langArray = SSystemLanguage::model()->findByAttributes(array('code'=>$lang));
 $categoryTrans=StoreCategoryTranslate::model()->findAllByAttributes(array('language_id'=>$langArray->id));
// Create breadcrumbs
$ancestors = $this->model->excludeRoot()->ancestors()->findAll();

foreach($ancestors as $c){
    foreach($categoryTrans as $ct){
        if($ct->object_id==$c->id)
       $this->breadcrumbs[$ct->name] = $c->getViewUrl();
    }
}

$this->breadcrumbs[] = $this->model->name;

?>

<!-- breadcrumbs (begin) -->
<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link(Yii::t('main','Home page'), array('/store/index/index')),
        'links'=>$this->breadcrumbs,
    ));
?>
<!-- breadcrumbs (end) -->

<div class="g-clearfix">

    <?php $this->renderFile(Yii::getPathOfAlias('pages.views.pages.left_sidebar').'.php', array('popup'=>'city-catalog')); ?>

    <!-- col-2 (begin) -->
    <div class="col-2">
        <!-- region-popup (begin) -->
        <div class="sorts">
            <div class="sort sort-reg">
                
                <?php $this->renderFile(Yii::getPathOfAlias('pages.views.pages.popup_regions').'.php'); ?>
                
            </div>
        </div>
        <!-- region-popup (end) -->
        <h1 class="page-title"><?php echo CHtml::encode($this->model->name); ?></h1>
        <!-- sorts (begin) -->
        <div class="sorts g-clearfix">
            <div class="sort sort-type">
                <?=Yii::t('StoreModule.core','Sort by:')?>
                <a href="#" title="" class="drop-link"><?=Yii::t('StoreModule.core', 'Last added')?></a>
                <div class="sort-popup hidden">
				<ul class="sort-dropdown inpage">
                    
                        <li><a href="/<?=$this->model->full_path.'/sort/created' ?>" title="Last added"><?echo Yii::t('StoreModule.core', 'Last added')?></a></li>
                        <li><a href="/<?=$this->model->full_path.'/sort/price' ?>" title="Cheap first"><?echo Yii::t('StoreModule.core', 'Cheap first')?></a></li>
                        <li><a href="/<?=$this->model->full_path.'/sort/price.desc' ?>" title="Highest first"><?echo Yii::t('StoreModule.core', 'Highest first')?></a></li>
                </ul>
                </div>
            </div>
            <div class="cost">
                <?=Yii::t('StoreModule.core', 'Sort by price')?>($):
                
                <?php
                $filterPrices = array(
                    0 => array(
                        'min' => 0,
                        'max' => 20
                    ),
                    1 => array(
                        'min' => 20,
                        'max' => 40
                    ),
                    2 => array(
                        'min' => 40,
                        'max' => 70
                    ),
                    3 => array(
                        'min' => 70,
                        'max' => 100
                    ),
                    4 => array(
                        'min' => 100,
                        'max' => 100000
                    ),
                );
                $countFilters = count($filterPrices);
                
                foreach($filterPrices as $key => $filter):
                ?>
                <a <?=($_GET['min_price'] == $filter['min'] && $_GET['max_price'] == $filter['max']) ? "class='active'" : ""?> title="<?=$filter['min']?>-100 $" href="/<?=$this->model->full_path?>/min_price/<?=$filter['min']?>/max_price/<?=$filter['max']?>">
                    <?php if($key == 0):?>
                        <?=Yii::t('StoreModule.core', 'under')?> <?=$filter['max'];?>
                    <?php elseif(($key+1) == $countFilters):?>
                        <?=Yii::t('StoreModule.core', 'over')?> <?=$filter['min'];?>
                    <?php else:?>
                        <?=$filter['min'];?>-<?=$filter['max'];?>
                    <?php endif;?>
                </a>
                <?php endforeach;?>
            </div>
        </div>
        <!-- sorts (end) -->

        <!-- products (begin) -->
        <div class="products catalog g-clearfix">
            
            <?php
                $this->widget('zii.widgets.CListView', array(
                    'dataProvider'=>$provider,
                    'ajaxUpdate'=>false,
                    'template'=>'{items} {pager}',
                    'itemView'=>$itemView,
                    'sortableAttributes'=>array(
                        'name', 'price'
                    ),
                                    
                ));
            ?>
            
        </div>
        <!-- products (end) -->

        <!-- sorts (begin) -->
        <div class="sort sort-page">
            <?=Yii::t('StoreModule.core','Per page:')?>
            <a href="#" title="" class="drop-link">24</a>
            <div class="sort-popup hidden">
                <ul class="sort-dropdown inpage">
                    
                        <li><a href="/<?=$this->model->full_path.'/per_page/24' ?>" title="">24</a></li>
                        <li><a href="/<?=$this->model->full_path.'/per_page/48' ?>" title="">48</a></li>
                        <li><a href="/<?=$this->model->full_path.'/per_page/96' ?>" title="">96</a></li>
                </ul>
            </div>
        </div>
        <!-- sorts (end) -->
        
        <?php if(!empty($this->model->description)): ?>
        <!-- b-page-text (begin) -->
        <div class="b-page-text text ">
            <h2 class="title"><?php echo CHtml::encode($this->model->name); ?></h2>
            <?php echo $this->model->description ?>
        </div>
        <!-- b-page-text (end) -->
        <?php endif ?>
    </div>
    <!-- col-2 (end) -->
</div>