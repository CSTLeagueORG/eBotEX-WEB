<?php

	namespace app\controllers;

	use app\models\Matches\Maps;
	use app\models\Matches\MatchesForm;
	use app\models\Servers\Servers;
	use Yii;
	use app\models\Matches\Matches;
	use app\models\Matches\MatchesSearch;
	use yii\web\Controller;
	use yii\web\ForbiddenHttpException;
	use yii\web\NotFoundHttpException;
	use yii\filters\VerbFilter;

	/**
	 * MatchesController implements the CRUD actions for Matches model.
	 */
	class MatchesController extends Controller {
		/**
		 * @inheritdoc
		 */
		public function behaviors () {
			return [
				'verbs' => [
					'class'   => VerbFilter::className(),
					'actions' => [
						'delete' => ['POST'],
					],
				],
			];
		}

		/**
		 * Lists all Matches models.
		 *
		 * @return mixed
		 */
		public function actionIndex () {
			$searchModel = new MatchesSearch();
			$searchModel->status = ["<", 14];
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

			return $this->render('index', [
				'searchModel'  => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		}

		/**
		 * Lists all Matches models.
		 *
		 * @return mixed
		 */
		public function actionAll () {
			$searchModel = new MatchesSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

			return $this->render('index', [
				'searchModel'  => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		}

		/**
		 * Lists archived Matches models.
		 *
		 * @return mixed
		 */
		public function actionArchive () {
			$searchModel = new MatchesSearch();
			$params = Yii::$app->request->queryParams;
			$params['MatchesSearch']['status'] = '14';
			$dataProvider = $searchModel->search($params);

			return $this->render('index', [
				'searchModel'  => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		}

		/**
		 * Displays a single Matches model.
		 *
		 * @param string $id
		 * @return mixed
		 */
		public function actionView ($id) {
			return $this->render('view', [
				'model' => $this->findModel($id),
			]);
		}

		/**
		 * Creates a new Matches model.
		 * If creation is successful, the browser will be redirected to the 'index' page.
		 *
		 * @return mixed
		 */
		public function actionCreate () {
			if(Yii::$app->user->isGuest or !Yii::$app->user->identity->is_admin) {
				throw new ForbiddenHttpException(Yii::t('app', 'You must be admin'));
			}
			$model = new MatchesForm();

			if($model->load(Yii::$app->request->post()) && $model->createMatch()) {
				return $this->redirect(['index']);
			} else {
				return $this->render('create', [
					'model' => $model,
				]);
			}
		}

		/**
		 * Updates an existing Matches model.
		 * If update is successful, the browser will be redirected to the 'view' page.
		 *
		 * @param string $id
		 * @return mixed
		 */
		public function actionUpdate ($id) {
			if(Yii::$app->user->isGuest or !Yii::$app->user->identity->is_admin) {
				throw new ForbiddenHttpException(Yii::t('app', 'You must be admin'));
			}
			$model = new MatchesForm();

			$model->loadMatch($match = Matches::find()->where(['id' => $id])->one());
			if($model->load(Yii::$app->request->post()) && $model->updateMatch($match)) {
				return $this->redirect(['view', 'id' => $match->id]);
			} else {
				return $this->render('update', [
					'model' => $model,
					'match' => $match,
				]);
			}
		}

		/**
		 * Deletes an existing Matches model.
		 * If deletion is successful, the browser will be redirected to the 'index' page.
		 *
		 * @param string $id
		 * @return mixed
		 */
		public function actionControls ($id) {
			$model = $this->findModel($id);

			$this->layout = false;

			return $this->render('_match_controls', [
						'model' => $model,
			]);
		}

		/**
		 * Deletes an existing Matches model.
		 * If deletion is successful, the browser will be redirected to the 'index' page.
		 *
		 * @param string $id
		 * @return mixed
		 */
		public function actionDelete ($id) {
			if(Yii::$app->user->isGuest or !Yii::$app->user->identity->is_admin) {
				throw new ForbiddenHttpException(Yii::t('app', 'You must be admin'));
			}
			$this->findModel($id)->delete();
			return $this->redirect(['index']);
		}

		/**
		 * Starts an existing Matches model.
		 * If start is successful, the browser will be redirected to the 'index' page.
		 *
		 * @param string $id
		 * @return mixed
		 */
		public function actionStart ($id) {
			if(Yii::$app->user->isGuest or !Yii::$app->user->identity->is_admin) {
				throw new ForbiddenHttpException(Yii::t('app', 'You must be admin'));
			}
			$match = $this->findModel($id);
			if ($match->enable !=0 or $match->status != 0) {
				if (!Yii::$app->session->hasFlash('success'))
					Yii::$app->session->setFlash('error', Yii::t('app', 'This match is already started'));
				return $this->redirect(['index']);
			}
			$server = $match->server;
			if (!$server) {
				$servers = Servers::find()->all();
				foreach($servers as $server) {
					/** @var $server Servers */
					if (count($server->getMatches()->where('status < 13')->andWhere('status > 0')->all()) == 0)
						break;
				}
			}

			if (count($server->getMatches()->where('status < 13')->andWhere('status > 0')->all()) != 0) {
				Yii::$app->session->setFlash('error', Yii::t('app', 'Can\'t find empty server'));
				return $this->redirect(['index']);
			}

			$match->ip = $server->ip;
			$match->server_id = $server->id;
			$match->enable = 1;
			$match->status = 1;
			$match->score_a = 0;
			$match->score_b = 0;
			if ($match->config_authkey == '')
				$match->config_authkey = uniqid(mt_rand(), true);
			$match->save();
			Yii::$app->session->setFlash('success', Yii::t('app', "Match will be started on") . " " . $server->ip);
			return $this->redirect(['index']);
		}

		/**
		 * Duplicates an existing Matches model.
		 * If duplication is successful, the browser will be redirected to the 'index' page.
		 *
		 * @param string $id
		 * @return mixed
		 */
		public function actionDuplicate ($id) {
			if(Yii::$app->user->isGuest or !Yii::$app->user->identity->is_admin) {
				throw new ForbiddenHttpException(Yii::t('app', 'You must be admin'));
			}
			$originalMatch = $this->findModel($id);
			$match = new Matches();
			$match->attributes = $originalMatch->attributes;
			$match->status = 0;
			$match->score_a = 0;
			$match->score_b = 0;
			$match->is_paused = null;
			$match->enable = 0;
			$match->ingame_enable = null;
			$match->config_authkey = MatchesForm::randString(127);
			$match->created_at = date('Y-m-d H:i:s');
			$match->updated_at = date('Y-m-d H:i:s');
			$match->insert();

			foreach($originalMatch->maps as $originalMap) {
				$map = new Maps();
				$map->attributes = $originalMap->attributes;
				$map->match_id = $match->id;
				$map->score_1 = 0;
				$map->score_2 = 0;
				$map->status = 0;
				$map->tv_record_file = null;
				$map->created_at = date('Y-m-d H:i:s');
				$map->updated_at = date('Y-m-d H:i:s');
				$map->save();
			}

			$match->refresh();
			$match->current_map = $match->maps[0]->id;
			$match->updated_at = date('Y-m-d H:i:s');
			$match->save();

			return $this->redirect(['index']);
		}

		/**
		 * Finds the Matches model based on its primary key value.
		 * If the model is not found, a 404 HTTP exception will be thrown.
		 *
		 * @param string $id
		 * @return Matches the loaded model
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		protected function findModel ($id) {
			if(($model = Matches::findOne($id)) !== null) {
				return $model;
			} else {
				throw new NotFoundHttpException(Yii::t('app', 'The requested pe does not exist.'));
			}
		}
	}
