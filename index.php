<?php
/*if($_SERVER['REMOTE_ADDR'] == '80.78.51.210'){*/

define('VERSION', '1.5');
error_reporting(1);

$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

require $yii;
require 'protected/components/SWebApplication.php';

Yii::createApplication('SWebApplication', $config)->run();
/*}else{
	echo "<img src='cooming.jpg' style='display:block; margin:0 auto' />";
}
*/