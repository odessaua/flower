<style>
    .ncity-link{
        margin: 5px 0 5px 5px;
        display: block;
    }
</style>
<h2><?=Yii::t('main','All cities with delivery');?></h2>
<?php
if(!empty($cities)){
    $parts = 6;
    if(sizeof($cities) >= $parts){
        $part_size = ceil((sizeof($cities) / $parts));
        $cities_chunked = array_chunk($cities, $part_size);
    }
    else{
        $cities_chunked[0] = $cities;
    }
    foreach ($cities_chunked as $city_chunk) {
        ?>
<div style="width: <?=(100 / $parts);?>%; float: left; padding: 20px 0 40px;">
        <?php
        foreach ($city_chunk as $city) {
            echo CHtml::link($city['name'], CSlug::url_slug($city['ename']), array('class' => 'ncity-link'));
        }
        ?>
</div>
        <?php
    }
}
?>
