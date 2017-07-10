<?php
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'sxyx',
	'timeZone' => 'Asia/Shanghai',
	'language' => 'zh_cn',
	'preload'=>array('log'),
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.vendor.*',
	),
	'defaultController'=>'site/index',

	'modules'=>array(
			'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'111111',
        		),
	),

	'components'=>array(
		'viewRenderer'=>array(
			'class'=>'application.extensions.yiiext.renderers.smarty.ESmartyViewRenderer',
			'fileExtension' => '.html',
		),
		'db'=>array(
			'connectionString' => 'mysql:host=127.0.0.1;dbname=sxyx',
			'schemaCachingDuration' => 3306,
			'emulatePrepare' => true,
			'enableParamLogging' => true,
			'username' => 'root',
			'password' => '111111',
			'charset' => 'utf8',
			'tablePrefix' => '',
		),

		'contentCompactor' => array(
			'class' => 'application.extensions.contentCompactor.ContentCompactor',
			'options' => array(
				'compress_scripts' => false,
			),
		),
		'request' => array(
			'class' => 'application.components.WebHttpRequest',
			'enableCsrfValidation' => false,
			'csrfUrl' => array(
				'/ajax/upfile',
			),
		),

		'wechat' => array(
			'class' => 'application.vendor.oauth.WeChat',
			'appID' => '111',
			'appSecret' => '222',
		),

		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
			'showScriptName' => false,
			'urlFormat'=>'path',
			'rules'=>array(
			   
				'/index' => 'site/index',
				'/data' => 'site/data',
			

			),
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				array(
					 'class' => 'CWebLogRoute',
					 'levels' => 'profile,trace',
					 'categories'=>'system.db.*',
					 'showInFireBug'=>true,
				),
			),
		),
	),
	'params'=>array(
		'hashkey' => '~!@~!#@$#@$!$!$~!$@@!~$',
		
		
	),
);
