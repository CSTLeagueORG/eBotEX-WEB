<?php
	namespace app\assets;

	use yii\web\AssetBundle;

	/**
	 * @author Qiang Xue <qiang.xue@gmail.com>
	 * @since 2.0
	 */
	class AppPNotifyAsset extends AssetBundle {
		public $sourcePath = '@bower/../sciactive/pnotify/dist';
		public $css = [
			'pnotify.css',
		];
		public $js = [
			'pnotify.js',
			'pnotify.desktop.js',
		];
		public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
	}
