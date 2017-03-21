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
            echo CHtml::link($city['name'], strtolower($city['eng_name']), array('class' => 'ncity-link'));
        }
        ?>
        </div>
        <?php
        }
    }
    echo '</div>';
}
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