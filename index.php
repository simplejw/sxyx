<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

/* 有效期设置 */
define('VALIDATE_EXPIRED', 		3600);

require_once($yii);
$app = Yii::createWebApplication($config);
if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0') === false) {
	$app->attachBehavior('WebBehavior', 'application.behavior.WebBehavior');
}

$app->run();
