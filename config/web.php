<?php

	$params = require(__DIR__ . '/ebotex.php');
	$main_config = require(__DIR__ . '/config.php');

	$params['countries'] = [

		'DZ' => 'ALGERIA',
		'AR' => 'ARGENTINA',
		'AU' => 'AUSTRALIA',
		'AT' => 'AUSTRIA',
		'BY' => 'BELARUS',
		'BE' => 'BELGIUM',
		'BR' => 'BRAZIL',
		'BG' => 'BULGARIA',
		'CA' => 'CANADA',
		'CL' => 'CHILE',
		'CN' => 'CHINA',
		'CC' => 'CIS',
		'HR' => 'CROATIA',
		'CZ' => 'CZECH REPUBLIC',
		'DK' => 'DENMARK',
		'EE' => 'ESTONIA',
		'EU' => 'EUROPE',
		'FI' => 'FINLAND',
		'FR' => 'FRANCE',
		'DE' => 'GERMANY',
		'GR' => 'GREECE',
		'HU' => 'HUNGARY',
		'IS' => 'ICELAND',
		'IN' => 'INDIA',
		'ID' => 'INDONESIA',
		'IR' => 'IRAN',
		'IE' => 'IRELAND',
		'IL' => 'ISRAEL',
		'IT' => 'ITALY',
		'JP' => 'JAPAN',
		'KZ' => 'KAZAKHSTAN',
		'KR' => 'KOREA',
		'LV' => 'LATVIA',
		'LY' => 'LIBYA',
		'LT' => 'LITHUANIA',
		'LU' => 'LUXEMBOURG',
		'MK' => 'MACEDONIA',
		'MY' => 'MALAYSIA',
		'MX' => 'MEXICO',
		'NL' => 'NETHERLANDS',
		'NZ' => 'NEW ZEALAND',
		'NO' => 'NORWAY',
		'PK' => 'PAKISTAN',
		'PE' => 'PERU',
		'PH' => 'PHILIPPINES',
		'PL' => 'POLAND',
		'PT' => 'PORTUGAL',
		'RO' => 'ROMANIA',
		'RU' => 'RUSSIAN FEDERATION',
		'SA' => 'SAUDI ARABIA',
		'RS' => 'SERBIA',
		'SG' => 'SINGAPORE',
		'SK' => 'SLOVAKIA',
		'SI' => 'SLOVENIA',
		'ZA' => 'SOUTH AFRICA',
		'ES' => 'SPAIN',
		'SE' => 'SWEDEN',
		'CH' => 'SWITZERLAND',
		'TW' => 'TAIWAN',
		'TH' => 'THAILAND',
		'TR' => 'TURKEY',
		'UA' => 'UKRAINE',
		'AE' => 'UNITED ARAB EMIRATES',
		'GB' => 'UNITED KINGDOM',
		'US' => 'UNITED STATES',
		'VE' => 'VENEZUELA',
		'TN' => 'TUNISIA',
		'MA' => 'Morocco',
		'HK' => 'Hong Kong',
		'VN' => 'VIETNAM',
		'MN' => 'MONGOLIA',
	];

	$config = [
		'id'           => 'basic',
		'basePath'     => dirname(__DIR__),
		'bootstrap'    => ['log'],
		'components'   => [
			'request'      => [
				// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
				'cookieValidationKey' => '0LKfUWRrzKu3VvtolIdClyGn4tBiI3Y_',
			],
			'cache'        => [
				'class' => 'yii\caching\FileCache',
			],
			'user'         => [
				'identityClass' => 'app\models\Users\User',
				'enableAutoLogin' => true
			],
			'errorHandler' => [
				'errorAction' => 'main/error',
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
						'class'      => 'yii\log\FileTarget',
						'logFile'    => '@app/runtime/logs/eauth.log',
						'categories' => ['nodge\eauth\*'],
						'logVars'    => [],
					],
				],
			],
			'db'           => require(__DIR__ . '/db.php'),
			'i18n'         => [
				'translations' => [
					'app*'  => [
						'class'          => 'yii\i18n\PhpMessageSource',
						'basePath'       => '@app/messages',
						'sourceLanguage' => 'en',
						'fileMap'        => [
							'app'       => 'app.php',
							'app/error' => 'error.php',
						],
					],
					'eauth' => [
						'class'    => 'yii\i18n\PhpMessageSource',
						'basePath' => '@eauth/messages',
					],
				],
			],

			'urlManager' => [
				'enablePrettyUrl' => true,
				'showScriptName'  => false,
				'rules'           => [
					'login/<service:steam>' => 'main/login',
					'matches/view/<id:\d+>' => 'matches/view',
				],
			],
			'eauth'      => [
				'class'       => 'nodge\eauth\EAuth',
				'popup'       => true, // Use the popup window instead of redirecting.
				'cache'       => false,
				// Cache component name or false to disable cache. Defaults to 'cache' on production environments.
				'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
				'httpClient'  => [
					// uncomment this to use streams in safe_mode
					//'useStreamsFallback' => true,
				],
				'services'    => [ // You can change the providers and their classes.
					'steam' => [
						'class'  => 'nodge\eauth\services\SteamOpenIDService',
						//'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
						'apiKey' => $main_config['api_key'],
						// Optional. You can get it here: https://steamcommunity.com/dev/apikey
					],
				],
			],
			/**/
		],
		'name'         => 'eBotEX',
		'version'      => '0.1',
		'params'       => $params,
		'defaultRoute' => 'main',
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
