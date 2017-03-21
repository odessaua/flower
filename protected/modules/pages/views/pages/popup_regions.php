<?php
if(!$popup)
	$popup = "city-simple";
?>

<?=Yii::t('main','Delivery region')?>: 
<a href="#" title="" class="drop-link cityName">
	<?php if(isset(Yii::app()->session['_city'])):?>
		<?php 
			$lang= Yii::app()->language;
                    if($lang == 'ua')
                        $lang = 'uk';

                $langArray = SSystemLanguage::model()->findByAttributes(array('code'=>$lang));
                $id= Yii::app()->db->createCommand()
				    ->select('object_id')
				    ->from('cityTranslate ct')
				    ->where('ct.name=:ct_name',array(':ct_name'=>Yii::app()->session['_city']))
				    ->queryRow();

            	$ct=Yii::app()->db->createCommand()
			    ->select('name,')
			    ->from('cityTranslate')
			    ->where('language_id=:lang_id',array('lang_id'=>$langArray->id))
			    ->andWhere('object_id=:city_id',array('city_id'=>$id['object_id']))
			    ->queryRow();
				// var_dump($langArray->id);
			echo $ct['name'];
			?>	
	<?php else:?>
		<?=Yii::t('main','Kyiv')?>
	<?php endif;?>
</a>
<div class="sort-popup hidden">
    <h2 class="title"><?=Yii::t('main','Send flowers to any city')?></h2>
    <p><?=Yii::t('main','Start typing the name of the city, and we will help')?></p>
    
    <?php 
	$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
		'name'=>$popup,
		'source'=>Yii::app()->createUrl('/site/autocompleteCity'),
		// additional javascript options for the autocomplete plugin
		'options'=>array(
			'minLength' => '2',
			'showAnim'=>'fold',
			 'search' =>'js: function() {
	            var term = this.value.split(/,s*/).pop();
	            if(term.length < 2)
	                return false;
	         }',
	         'change' => 'js: function(event,ui){
	         	var city = ui.item.value;	
				$.ajax({
					type: "GET",
					url: "/site/changeCity",
					data: {city : city},
					dataType: "text",
					success: function(data){
					    var city = data.split("_");
					    if(city.length == 2){
					        document.location.href="/"+city[1];
					    }
					    $(".cityName").text(city[0]);
						$(".sort-popup").addClass("hidden");

					}
				});
	         }',
	        'focus' =>'js: function() {
	            return false;
	         }',
		),
		'htmlOptions' => array(
			'placeholder'=>Yii::t('main','Enter the city of delivery'),
		),
	));
	?>
    
    <div class="h-regions">
        <div class="regions" style="width: 100%;">
            <h2 class="title">
                <?=Yii::t('main','Ukraine')?>
                <?= CHtml::link(Yii::t('main','All cities with delivery'), '/all-cities', array('class' => 'all-cities')); ?>
            </h2>

<!--            <ul>-->
<!--            	<li>-->
<!--            		<ul>-->
            	<?php
            	$count=0;
            	$lang= Yii::app()->language;
                    if($lang == 'ua')
                        $lang = 'uk';

                $langArray = SSystemLanguage::model()->findByAttributes(array('code'=>$lang));
            	$cities = Yii::app()->db->createCommand()
				    ->select('c.name as ename,ct.name,ct.object_id,c.id,ct.language_id,ctt.name as eng_name')
				    ->from('city c')
				    ->join('cityTranslate ct', 'c.id=ct.object_id')
				    ->join('cityTranslate ctt', 'c.id=ctt.object_id')
				    ->where('ct.language_id=:id', array(':id'=>$langArray->id))
				    ->andWhere('c.show_in_popup=1')
				    ->andWhere('ctt.language_id=:eid', array(':eid'=>9))
					->order('ct.name, id desc')
				    ->queryAll();

                if(!empty($cities)){
                    $parts = 3;
                    if(sizeof($cities) >= $parts){
                        $part_size = ceil((sizeof($cities) / $parts));
                        $cities_chunked = array_chunk($cities, $part_size);
                    }
                    else{
                        $cities_chunked[0] = $cities;
                    }
                    foreach ($cities_chunked as $city_chunk) {
                        ?>
                        <div style="width: <?=(100 / $parts);?>%; float: left;">
                            <ul>
                            <?php
                            foreach ($city_chunk as $city) {
                            ?>
                            <li>
                                <?= CHtml::link($city['name'], '/' . strtolower($city['eng_name'])); ?>
                            </li>
                            <?php
                            }
                            ?>
                            </ul>
                        </div>
                    <?php
                    }
                }
				// var_dump($citys[0]['name']);
            	// var_dump($langArray->id);
            	/*for($i=0;$i<count($citys);$i++){
            		// if($i==0)
            		// 	continue;
            		// if($count != 0 && $count%3 == 0){
            		// 	$count = 0;
            	?>
	            		<!-- </ul></li><li><ul> -->
	            <?php //} ?>
	            		<!-- <li> -->
	                        <li><a title="" href="#"><?=$citys[$i]['name']?></a></li>
	                     
	            <?php 
	                	$count++;
	            ?>
	                		
	                

                <?php }*/ ?>
<!--            </ul>-->
        </div>
    </div>
</div>