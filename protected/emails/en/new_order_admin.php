<html>
<body>

 

  <?=$email?>
  <?=$phone?>
  <?=$name?>
  <?=$id?>
  <?=$quantity?>
<?php $model=StoreProduct::model()->findByAttributes(array('id'=>$id)); echo $model->name;?>


</body>
</html>