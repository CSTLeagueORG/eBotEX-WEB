<?php

	use yii\helpers\Html;
	use yii\grid\GridView;
	use yii\widgets\Pjax;
	use yii\helpers\Url;
	use \app\models\Matches\Matches;
	use \app\models\Teams\Teams;

	/* @var $this yii\web\View */
	/* @var $searchModel app\models\Matches\MatchesSearch */
	/* @var $dataProvider yii\data\ActiveDataProvider */

	$this->title = Yii::t('app', 'Matches');
	$this->params['breadcrumbs'][] = $this->title;
?>

<div class="matches-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<? if(!Yii::$app->user->isGuest and Yii::$app->user->identity->is_admin): ?>
		<p>
			<?= Html::a(Yii::t('app', 'Create new match'), ['create'], ['class' => 'btn btn-success']) ?>
		</p>
	<? endif ?>
	<?php Pjax::begin(); ?>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns'      => [
			'id',
			[
				'attribute'      => Yii::t('app', 'Team A'),
				'format'         => 'raw',
				'contentOptions' => function (Matches $model) {
					return [
						'class' => 'text-left',
						'id' => 'team_a-' . $model->id,
					];
				},
				'headerOptions'  => ['class' => 'text-left'],
				'value'          => function (Matches $model) {
					return "
						<span class=\"" . (($model->currentMap->current_side == 'ct')? 'text-primary' : 'text-warning') . "\">
							" . (($model->teamA)? $model->teamA->name : $model->team_a_name) . "
							" . ((strlen($model->team_a_flag) == 2)? '<i class="teamflag teamflag-' . strtolower($model->team_a_flag) . '"></i> ' : '') . "
						</span>
					";
				}
			],
			[
				'attribute'      => Yii::t('app', 'Score'),
				'format'         => 'raw',
				'contentOptions' => function (Matches $model) {
					return [
						'class' => 'text-center',
						'id' => 'score-' . $model->id,
					];
				},
				'headerOptions'  => ['class' => 'text-center'],
				'value'          => function (Matches $model) {
					return "
						<span class=\"" . (($model->score_a <= $model->score_b)? ($model->score_a == $model->score_b)? '' : 'text-danger' : 'text-success') . "\">
							" . $model->score_a . "
						</span>
						—
						<span class=\"" . (($model->score_b <= $model->score_a)? ($model->score_a == $model->score_b)? '' : 'text-danger' : 'text-success') . "\">
							" . $model->score_b . "
						</span>
					";
				}
			],
			[
				'attribute'      => Yii::t('app', 'Team B'),
				'format'         => 'raw',
				'contentOptions' => function (Matches $model) {
					return [
						'class' => 'text-right',
						'id' => 'team_b-' . $model->id,
					];
				},
				'headerOptions'  => ['class' => 'text-right'],
				'value'          => function (Matches $model) {
					return "
						<span class=\"" . (($model->currentMap->current_side == 'ct')? 'text-warning' : 'text-primary') . "\">
							" . ((strlen($model->team_b_flag) == 2)? '<i class="teamflag teamflag-' . strtolower($model->team_b_flag) . '"></i>' : '') . "
							" . (($model->teamB)? $model->teamB->name : $model->team_b_name) . "
						</span>
					";
				}
			],
			[
				'attribute' => Yii::t('app', 'Connect link'),
				'format'    => 'raw',
				'value'     => function (Matches $model) {
					return $model->enable ? $model->server ? "<a href=\"steam://connect/{$model->ip}/{$model->config_password}\">{$model->server->hostname}</a>" : Yii::t('app', 'Random') : Yii::t('app', 'Match stopped');
				},
			],
			[
				'attribute' => Yii::t('app', 'Season'),
				'format'    => 'raw',
				'value'     => function (Matches $model) {
					return ($model->season)? $model->season->name : '';
				},
			],
			[
				'attribute' => Yii::t('app', 'Status'),
				'format'    => 'raw',
				'value'     => function (Matches $model) {
					$result = '';
					if(($model->status == 1 and $model->enable) and $model->status != 14) {
						$result = "<div class='label label-primary' id='flag-{$model->id}'><i class='glyphicon glyphicon-flag'></i></div> ";
					} elseif(($model->status == 0 or $model->status == 13 or !$model->enable) and $model->status != 14) {
						$result = "<div class='label label-danger' id='flag-{$model->id}'><i class='glyphicon glyphicon-flag'></i></div> ";
					} elseif($model->status > 0 and $model->status < 13) {
						$result = ($model->is_paused)? "<div class='label label-warning' id='flag-{$model->id}'><i class='glyphicon glyphicon-flag'></i></div> " : "<div class='label label-success' id='flag-{$model->id}'><i class='glyphicon glyphicon-flag'></i></div> ";
					}
					switch ($model->status) {
						case 0:
							$result .= Yii::t('app', 'Not started');
							break;
						case 1:
							$result .= Yii::t('app', 'Starting');
							break;
						case 2:
							$result .= Yii::t('app', 'Warmup knife');
							break;
						case 3:
							$result .= Yii::t('app', 'Knife round');
							break;
						case 4:
							$result .= Yii::t('app', 'Side picking');
							break;
						case 5:
							$result .= Yii::t('app', 'Warmup 1st side');
							break;
						case 6:
							$result .= Yii::t('app', '1st side');
							break;
						case 7:
							$result .= Yii::t('app', 'Warmup 2nd side');
							break;
						case 8:
							$result .= Yii::t('app', '2nd side');
							break;
						case 9:
							$result .= Yii::t('app', 'OT warmup 1st side');
							break;
						case 10:
							$result .= Yii::t('app', 'OT 1st side');
							break;
						case 11:
							$result .= Yii::t('app', 'OT warmup 2nd side');
							break;
						case 12:
							$result .= Yii::t('app', 'OT 2nd side');
							break;
						case 13:
							$result .= Yii::t('app', 'Match over');
							break;
						case 14:
							$result .= Yii::t('app', 'Archived');//<div class=\'label label-default\'><i class=\'fa fa-archive\'></i></div>
							break;
						default:
							$result .= Yii::t('app', 'Unrecognised');
							break;
					}
					return $result;
				},
			],
			[
				'attribute' => Yii::t('app', 'Actions'),
				'format'    => 'raw',
				'value'     => function (Matches $model) {
					return $this->render('_match_controls', [
						'model' => $model,
					]);
				},
			],

//			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>
	<?php Pjax::end(); ?>
</div>
