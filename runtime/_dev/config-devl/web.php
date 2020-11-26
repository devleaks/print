<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'language' => 'fr',
//    'language' => 'fr-FR',
	'name' => 'Jo and Z',
    'timeZone' => 'Europe/Brussels',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
		'log',
        'mdm\behaviors\ar\Bootstrap',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'TuB7_R-OcRsxuLBn7MbOZe4EOIpOdhRH',
        ],
        'assetManager' => [
            'linkAssets' => true,
        ], 
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'dektrium\user\models\User',
            'loginUrl' => ['/user/security/login'],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
	    'formatter' => [
	        'dateFormat' => 'dd.MM.yyyy',
	        'decimalSeparator' => ',',
	        'thousandSeparator' => ' ',
	        'currencyCode' => 'EUR',
	   ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
//        	'viewPath' => '@app/modules/order',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
	        'transport' => [
	            'class' => 'Swift_SmtpTransport',
	            'host' => 'smtp.gmail.com',
	            'username' => 'joz-srl@gmail.com',
	            'password' => '3NksdmksqmdlkM',
	            'port' => '587',
	            'encryption' => 'tls',
	        ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'i18n' => [
            'translations' => [
                'store'=> [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => "@app/messages",
                    'sourceLanguage' => 'en-US',
                ],
            ],
        ],
        'image' => [
                'class' => 'yii\image\ImageDriver',
                'driver' => 'GD',  //GD or Imagick
        ],
    ],
	'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            //'allowUnconfirmedLogin' => true,
            'confirmWithin' => 21600,
            'cost' => 12,
            'admins' => ['admin'],
        ],
		'gridview' =>  [
        	'class' => '\kartik\grid\Module'
	        // enter optional module parameters below - only if you need to  
	        // use your own export download action or custom translation 
	        // message source
	        // 'downloadAction' => 'gridview/export/download',
	        // 'i18n' => []
	    ],
		'datecontrol' =>  [
        	'class' => '\kartik\datecontrol\Module',
	        'displaySettings' => [
	            \kartik\datecontrol\Module::FORMAT_DATE => 'dd-MM-yyyy',
	            \kartik\datecontrol\Module::FORMAT_TIME => 'HH:mm:ss a',
	            \kartik\datecontrol\Module::FORMAT_DATETIME => 'dd-MM-yyyy HH:mm:ss a', 
	        ],
    	],
	    'markdown' => [
	        // the module class
	        'class' => 'kartik\markdown\Module',
			'smartyPants' => false,
	        'i18n' => [
	            'class' => 'yii\i18n\PhpMessageSource',
	            'basePath' => '@markdown/messages',
	            'forceTranslation' => true
        	],
		],
		/** Store management
		 *	Admin: Users, etc.
		 *	Store: Store management: items, prices, work items...
		 */
        'admin' => [ 'class' => 'app\modules\admin\Module' ],
        'store' => [ 'class' => 'app\modules\store\Module' ],
        'order' => [ 'class' => 'app\modules\order\Module' ],
        'accnt' => [ 'class' => 'app\modules\accnt\Module' ],
        'work' =>  [ 'class' => 'app\modules\work\Module' ],
		/**
		 */
	],
    'params' => $params,
];

if (YII_ENV_DEV) {
	$allowedIPs = array($_SERVER['REMOTE_ADDR']);
	
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
                    'class' => 'yii\debug\Module',
                    'allowedIPs' => $allowedIPs,
           ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
					'class' => 'yii\gii\Module',
					'allowedIPs' => $allowedIPs,
			];
}

return $config;
