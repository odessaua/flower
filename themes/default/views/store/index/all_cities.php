<?php
/**
 * @var $cities
 * @var $regions
 */
?>
<style>
    .ncity-link{
        margin: 5px 0 5px 5px;
        display: block;
    }
    .ac-region{
        float: left;
        margin-bottom: 20px;
    }
    .ac-region-title{
        color: #7a1c4a;
    }
</style>
<h2 style="margin-bottom: 20px;"><?=Yii::t('main','All cities with delivery');?></h2>
<?php
//var_dump($cities, $regions);
$parts = 5;
if(!empty($regions)){
    echo '<div class="ac-regions">';
    foreach($regions as $r_id => $region){
        if(empty($cities[$r_id])) continue;
        else{
        ?>
        <div class="ac-region" style="width: <?=(100/$parts);?>%;">
            <h3 class="ac-region-title"><?= $region['name']; ?></h3>
        <?php
        foreach($cities[$r_id] as $city){
            echo CHtml::link($city['name'], CSlug::url_slug($city['ename']), array('class' => 'ncity-link'));
        }
        ?>
        </div>
        <?php
        }
    }
    echo '</div>';
}
/*
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
}*/
?>
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
<script>
    jQuery(document).ready(function ($) {
        $('.ac-regions').masonry({
            // options
            itemSelector: '.ac-region'
        });
    });
</script>