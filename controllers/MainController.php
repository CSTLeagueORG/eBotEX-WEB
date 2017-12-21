<?php

	namespace app\controllers;

	use app\models\Users\Users;
	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;

	class MainController extends Controller {
		/**
		 * @inheritdoc
		 */
		public function behaviors () {
			return [
				'access' => [
					'class' => AccessControl::className(),
					'only'  => ['logout'],
					'rules' => [
						[
							'actions' => ['logout'],
							'allow'   => true,
							'roles'   => ['@'],
						],
					],
				],
				'verbs'  => [
					'class'   => VerbFilter::className(),
					'actions' => [
						'logout' => ['post'],
					],
				],
				'eauth'  => [
					// required to disable csrf validation on OpenID requests
					'class' => \nodge\eauth\openid\ControllerBehavior::className(),
					'only'  => ['login'],
				],
			];
		}

		/**
		 * @inheritdoc
		 */
		public function actions () {
			return [
				'error'   => [
					'class' => 'yii\web\ErrorAction',
				],
				'captcha' => [
					'class'           => 'yii\captcha\CaptchaAction',
					'fixedVerifyCode' => YII_ENV_TEST? 'testme' : null,
				],
			];
		}

		/**
		 * Displays homepage.
		 *
		 * @return string
		 */
		public function actionIndex () {
			return $this->render('index');
		}

		/**
		 * Login action.
		 *
		 * @return string
		 */
		public function actionLogin () {
			if(!Yii::$app->user->isGuest) {
				return $this->goHome();
			}

			$serviceName = Yii::$app->getRequest()->getQueryParam('service');
			if(isset($serviceName) && $serviceName === 'steam') {
				/** @var $eauth \nodge\eauth\ServiceBase */
				$eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
				$eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
				$eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('main/login'));

				try {
					if($eauth->authenticate()) {
//                  var_dump($eauth->getIsAuthenticated(), $eauth->getAttributes()); exit;

						$identity = Users::findByEAuth($eauth);
						Yii::$app->getUser()->login($identity);

						// special redirect with closing popup window
						$eauth->redirect();
					} else {
						// close popup window and redirect to cancelUrl
						$eauth->cancel();
					}
				} catch (\nodge\eauth\ErrorException $e) {
					// save error to show it later
					Yii::$app->getSession()->setFlash('error', 'EAuthException: ' . $e->getMessage());

					// close popup window and redirect to cancelUrl
//              $eauth->cancel();
					$eauth->redirect($eauth->getCancelUrl());
				}
			}

			return $this->render('login');
		}

		/**
		 * Logout action.
		 *
		 * @return string
		 */
		public function actionLogout () {
			Yii::$app->user->logout();

			return $this->goHome();
		}

		/**
		 * Displays about page.
		 *
		 * @return string
		 */
		public function actionAbout () {
			return $this->render('about');
		}
	}
