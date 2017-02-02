<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Matches */

$this->title = Yii::t('app', 'Match #') . $model->id . " " . (($model->teamA)? $model->teamA->name : $model->team_a_name) . ' vs ' . (($model->teamB)? $model->teamB->name : $model->team_b_name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Matches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="matches-view">

	<h1><?= Yii::t('app', 'Match #') . $model->id . " " . (($model->teamA)? $model->teamA->name : $model->team_a_name) . ' vs ' . (($model->teamB)? $model->teamB->name : $model->team_b_name) ?></h1>

	<div class="row">
		<div class="col-sm-8">
			<table class="table">
				<tr><th colspan="2"><i class="fa fa-wrench"></i> <?= Yii::t('app', 'Match configuration') ?></th></tr>
				<tr><td><?= Yii::t('app', 'Config') ?></td><td><?= $model->rules ?></td></tr>
				<tr><td><?= Yii::t('app', 'Max rounds') ?></td><td><?= $model->max_round ?></td></tr>
				<tr><td><?= Yii::t('app', 'Status') ?></td><td><?= $model->max_round ?></td></tr>
				<tr><td><?= Yii::t('app', 'Active') ?></td><td><span class="label label-<?= ($model->enable)? "success":"danger" ?>"><i class="fa fa-flag"></i></span></td></tr>
				<tr><td><?= Yii::t('app', 'Play all rounds') ?></td><td><span class="label label-<?= ($model->config_full_score)? "success":"danger" ?>"><i class="fa fa-flag"></i></span></td></tr>
				<tr><td><?= Yii::t('app', 'Wait for streamers') ?></td><td><span class="label label-<?= ($model->config_streamer)? "success":"danger" ?>"><i class="fa fa-flag"></i></span></td></tr>
				<tr><td><?= Yii::t('app', 'Overtimes enabled') ?><</td><td><span class="label label-<?= ($model->config_ot)? "success":"danger" ?>"><i class="fa fa-flag"></i></span></td></tr>
				<tr><td><?= Yii::t('app', 'Knife round') ?></td><td><span class="label label-<?= ($model->config_knife_round)? "success":"danger" ?>"><i class="fa fa-flag"></i></span></td></tr>
				<tr><td><?= Yii::t('app', 'Generate Heatmap') ?>></td><td><span class="label label-<?= ($model->config_heatmap)? "success":"danger" ?>"><i class="fa fa-flag"></i></span></td></tr>
			</table>
		</div>
		<div class="col-xs-4">
		</div>
	</div>

	<?/*= DetailView::widget([
		'model'      => $model,
		'attributes' => [
			'id',
			'ip',
			'server_id',
			'season_id',
			'team_a',
			'team_a_flag',
			'team_a_name',
			'team_b',
			'team_b_flag',
			'team_b_name',
			'status',
			'is_paused',
			'score_a',
			'score_b',
			'max_round',
			'rules',
			'overtime_startmoney',
			'overtime_max_round:datetime',
			'config_full_score',
			'config_ot',
			'config_streamer',
			'config_knife_round',
			'config_switch_auto',
			'config_auto_change_password',
			'config_password',
			'config_heatmap',
			'config_authkey',
			'enable',
			'map_selection_mode',
			'ingame_enable',
			'current_map',
			'force_zoom_match',
			'identifier_id',
			'startdate',
			'auto_start',
			'auto_start_time:datetime',
			'created_at',
			'updated_at',
		],
	]) */?>

</div>
