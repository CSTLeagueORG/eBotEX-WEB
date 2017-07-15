<?php

	namespace app\controllers;

	use Yii;
	use app\models\Seasons\Seasons;
	use app\models\Seasons\SeasonsSearch;
	use yii\web\Controller;
	use yii\web\NotFoundHttpException;
	use yii\filters\VerbFilter;
	use yii\web\ForbiddenHttpException;

	/**
	 * SeasonsController implements the CRUD actions for Seasons model.
	 */
	class SeasonsController extends Controller {
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
		 * Lists all Seasons models.
		 *
		 * @return mixed
		 */
		public function actionIndex () {
			$searchModel = new SeasonsSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

			return $this->render('index', [
				'searchModel'  => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		}

		/**
		 * Displays a single Seasons model.
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
		 * Creates a new Seasons model.
		 * If creation is successful, the browser will be redirected to the 'view' page.
		 *
		 * @return mixed
		 */
		public function actionCreate () {
			if(Yii::$app->user->isGuest or !Yii::$app->user->identity->is_admin) {
				throw new ForbiddenHttpException('You must be admin');
			}
			$model = new Seasons();

			if($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				return $this->render('create', [
					'model' => $model,
				]);
			}
		}

		/**
		 * Updates an existing Seasons model.
		 * If update is successful, the browser will be redirected to the 'view' page.
		 *
		 * @param string $id
		 * @return mixed
		 */
		public function actionUpdate ($id) {
			if(Yii::$app->user->isGuest or !Yii::$app->user->identity->is_admin) {
				throw new ForbiddenHttpException('You must be admin');
			}
			$model = $this->findModel($id);

			if($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				return $this->render('update', [
					'model' => $model,
				]);
			}
		}

		/**
		 * Deletes an existing Seasons model.
		 * If deletion is successful, the browser will be redirected to the 'index' page.
		 *
		 * @param string $id
		 * @return mixed
		 */
		public function actionDelete ($id) {
			if(Yii::$app->user->isGuest or !Yii::$app->user->identity->is_admin) {
				throw new ForbiddenHttpException('You must be admin');
			}
			$this->findModel($id)->delete();

			return $this->redirect(['index']);
		}

		/**
		 * Finds the Seasons model based on its primary key value.
		 * If the model is not found, a 404 HTTP exception will be thrown.
		 *
		 * @param string $id
		 * @return Seasons the loaded model
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		protected function findModel ($id) {
			if(($model = Seasons::findOne($id)) !== null) {
				return $model;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
	}
