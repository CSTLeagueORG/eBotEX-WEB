<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PlayersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Players');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="players-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a(Yii::t('app', 'Create Players'), ['create'], ['class' => 'btn btn-success']) ?>
	</p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],

			'id',
			'match_id',
			'map_id',
			'player_key',
			'team',
			// 'ip',
			// 'steamid',
			// 'first_side',
			// 'current_side',
			// 'pseudo',
			// 'nb_kill',
			// 'assist',
			// 'death',
			// 'point',
			// 'hs',
			// 'defuse',
			// 'bombe',
			// 'tk',
			// 'nb1',
			// 'nb2',
			// 'nb3',
			// 'nb4',
			// 'nb5',
			// 'nb1kill',
			// 'nb2kill',
			// 'nb3kill',
			// 'nb4kill',
			// 'nb5kill',
			// 'pluskill',
			// 'firstkill',
			// 'created_at',
			// 'updated_at',

			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>
</div>
