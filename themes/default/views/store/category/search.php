<?php

/**
 * Search view
 * @var $this CategoryController
 */

// Set meta tags
$this->pageTitle = Yii::t('StoreModule.core', 'Search');
$this->breadcrumbs[] = Yii::t('StoreModule.core', 'Search');

?>

<div class="catalog">

	<div class="products_list">
		<?php
			$this->widget('zii.widgets.CBreadcrumbs', array(
				'links'=>$this->breadcrumbs,
				'homeLink'=>Yii::t('main','Home page')
			));
		?>

		<h1><?php
			echo Yii::t('StoreModule.core', 'Search results');
			if(($q=Yii::app()->request->getParam('q')))
				echo ' "'.CHtml::encode($q).'"';
		?></h1>

		 <div class="sorts g-clearfix">
            <div class="sort sort-type">
                <?=Yii::t('StoreModule.core','Sort by:')?>
                <a href="#" title="" class="drop-link"><?=Yii::t('StoreModule.core', 'Last added')?></a>
                <div class="sort-popup hidden">
                    <?php
                    echo CHtml::dropDownList('sorter', Yii::app()->request->url, array(
                        Yii::app()->request->addUrlParam('/store/category/view', array('sort'=>'created'))  => Yii::t('StoreModule.core', 'Last added'),
                        Yii::app()->request->addUrlParam('/store/category/view', array('sort'=>'price'))  => Yii::t('StoreModule.core', 'Cheap first'),
                        Yii::app()->request->addUrlParam('/store/category/view', array('sort'=>'price.desc')) => Yii::t('StoreModule.core', 'Highest first'),
                    ), array('onchange'=>'applyCategorySorter(this)', 'class'=>'sort-dropdown'));
                    ?>
                </div>
            </div>
            <div class="cost">
                <?=Yii::t('StoreModule.core', 'Cost compositions')?>($):
                
                <?php
                $filterPrices = array(
					0 => array(
						'min' => 0,
						'max' => 15
					),
					1 => array(
						'min' => 15,
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
                		<?=Yii::t('StoreModule.core', 'to')?> <?=$filter['max'];?>
                	<?php elseif(($key+1) == $countFilters):?>
                		<?=Yii::t('StoreModule.core', 'of')?> <?=$filter['min'];?>
                	<?php else:?>
                		<?=$filter['min'];?>-<?=$filter['max'];?>
                	<?php endif;?>
                </a>
                <?php endforeach;?>
            </div>
        </div>
        <!-- sorts (end) -->
			


		</div>
		<?php
			if(isset($provider))
			{
				$this->widget('zii.widgets.CListView', array(
					'dataProvider'=>$provider,
					'ajaxUpdate'=>false,
					'template'=>'{items} {pager}',
					'itemView'=>'_product',
					'sortableAttributes'=>array(
						'name', 'price'
					),
				));
			}
			else
			{
				echo Yii::t('StoreModule.core', 'No results');
			}
		?>
		
	</div>
	<div class="sort sort-page">
            <?=Yii::t('StoreModule.core','Per page:')?>
            <a href="#" title="" class="drop-link">12</a>
            <div class="sort-popup hidden">
                <ul class="sort-dropdown inpage">
                    <!-- <select> -->
                     <?php
                    echo CHtml::dropDownList('sorter', Yii::app()->request->url, array(
                        Yii::app()->request->removeUrlParam('/store/category/view', 'per_page')  => '----',
                        Yii::app()->request->addUrlParam('/store/category/view', array('per_page'=>'12'))  => '12',
                        Yii::app()->request->addUrlParam('/store/category/view', array('per_page'=>'24'))  => '24',
                        Yii::app()->request->addUrlParam('/store/category/view', array('per_page'=>'48')) => '48',
                    ), array('onchange'=>'applyInPage(this)', 'class'=>'sort-dropdown'));
                    ?>
                    <!-- </select> -->
                </ul>
</div>