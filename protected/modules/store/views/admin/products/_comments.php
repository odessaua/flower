<?php 
$this->widget(
      'application.widget.emultiselect.EMultiSelect',
      array('sortable'=>true, 'searchable'=>true)
);?>

	
<label>Выберите регион доставки</label>
<?php 
$cities = City::model()->findAll();
$cities = CHtml::listData($cities, 'id', 'name');

$select = array();

if($model->isNewRecord)
	$productCities = StoreProductCityRef::model()->findAll();
else
	$productCities = StoreProductCityRef::model()->findAll(array('condition'=>'product_id='.$model->id));

foreach($productCities as $city)
{
	$select[] = $city->city_id;
}

echo CHtml::dropDownList('cities', $select, $cities,
	array(
		'multiple' => 'multiple',
		'style' => 'height:400px;',
		'class'=>'multiselect'
	));
?>