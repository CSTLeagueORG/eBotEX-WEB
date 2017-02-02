<?php

namespace app\controllers;

use Yii;
use app\models\Matches;
use app\models\MatchesSearch;
use yii\web\Controller;
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
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate () {
		$model = new Matches();

		if($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
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
	 * Deletes an existing Matches model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete ($id) {
		$this->findModel($id)->delete();

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
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
