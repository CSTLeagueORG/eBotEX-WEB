<?php

$params = require(__DIR__ . '/params.php');

$config = [
	'id'         => 'basic',
	'basePath'   => dirname(__DIR__),
	'bootstrap'  => ['log'],
	'components' => [
		'request'      => [
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => '0LKfUWRrzKu3VvtolIdClyGn4tBiI3Y_',
		],
		'cache'        => [
			'class' => 'yii\caching\FileCache',
		],
		'user'         => [
			'identityClass'   => 'app\models\User',
			'enableAutoLogin' => true,
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'mailer'       => [
			'class'            => 'yii\swiftmailer\Mailer',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.
			'useFileTransport' => true,
		],
		'log'          => [
			'traceLevel' => YII_DEBUG? 3 : 0,
			'targets'    => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
				[
					'class' => 'yii\log\FileTarget',
					'logFile' => '@app/runtime/logs/eauth.log',
					'categories' => ['nodge\eauth\*'],
					'logVars' => [],
				],
			],
		],
		'db'           => require(__DIR__ . '/db.php'),
		'i18n'         => [
			'translations' => [
				'app*' => [
					'class'          => 'yii\i18n\PhpMessageSource',
					'basePath'       => '@app/messages',
					'sourceLanguage' => 'en',
					'fileMap'        => [
						'app'       => 'app.php',
						'app/error' => 'error.php',
					],
				],
				'eauth' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@eauth/messages',
				],
			],
		],

		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
				'login/<service:google|facebook|etc>' => 'site/login',
			],
		],
		'eauth' => [
			'class' => 'nodge\eauth\EAuth',
			'popup' => true, // Use the popup window instead of redirecting.
			'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
			'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
			'httpClient' => [
				// uncomment this to use streams in safe_mode
				//'useStreamsFallback' => true,
			],
			'services' => [ // You can change the providers and their classes.
				'steam' => [
					'class' => 'nodge\eauth\services\SteamOpenIDService',
					//'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
					'apiKey' => '...', // Optional. You can get it here: https://steamcommunity.com/dev/apikey
				],
			],
		],
		/**/
	],
	'name' => 'eBotEX',
	'version' => '1.0',
	'params'     => $params,
];

if(YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class' => 'yii\debug\Module',
	];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
	];
}

return $config;
