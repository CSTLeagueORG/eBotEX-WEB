<?php

namespace app\controllers;

use Yii;
use app\models\Teams\Teams;
use app\models\Teams\TeamsInSeasons;
use app\models\Teams\TeamsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TeamsController implements the CRUD actions for Teams model.
 */
class TeamsController extends Controller {
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
	 * Lists all Teams models.
	 *
	 * @return mixed
	 */
	public function actionIndex () {
		$searchModel = new TeamsSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Lists all Teams models.
	 *
	 * @return mixed
	 */
	public function actionGetbyseasons () {
		if (!isset(Yii::$app->request->post()['season_id'])) {
			throw new NotFoundHttpException('Season not submitted');
		}
		$teams = TeamsInSeasons::find()->where(['season_id' => Yii::$app->request->post()['season_id']])->all();
		$result = [
			'id' => [],
			'name' => [],
			'flag' => [],
		];
		foreach($teams as $team) {
			/** @var $team TeamsInSeasons */
			array_push($result['id'], $team->team->id);
			array_push($result['name'], $team->team->name);
			array_push($result['flag'], $team->team->flag);
		}
		return json_encode($result);
	}

	/**
	 * Displays a single Teams model.
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
	 * Creates a new Teams model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate () {
		$model = new Teams();

		if($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Teams model.
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
	 * Deletes an existing Teams model.
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
	 * Finds the Teams model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param string $id
	 * @return Teams the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel ($id) {
		if(($model = Teams::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
