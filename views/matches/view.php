<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Matches\Matches */

$this->title = Yii::t('app', 'Match #') . $model->id . " " . (($model->teamA)? $model->teamA->name : $model->team_a_name) . ' vs ' . (($model->teamB)? $model->teamB->name : $model->team_b_name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Matches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<script>
	$(function() {
		$('#matchTabs a').click(function (e) {
			e.preventDefault();
			$(this).tab('show');
			if ($(this).attr("href") == "#match-stats") {
				generateTimeLine(1);
			}
		});
		$(".needTips").tooltip({placement: "bottom"});
		$(".needTips_S").tooltip({placement: "bottom"});
	});
</script>
<div class="matches-view">

	<h1><?= Yii::t('app', 'Match #') . $model->id . " " . (($model->teamA)? $model->teamA->name : $model->team_a_name) . ' vs ' . (($model->teamB)? $model->teamB->name : $model->team_b_name) ?></h1>
	<div>

		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist" id="matchTabs">
			<li role="presentation" class="active"><a href="#overview" aria-controls="overview" role="tab" data-toggle="tab"><?= Yii::t('app', 'Overview') ?></a></li>
			<li role="presentation"><a href="#match-stats" aria-controls="match-stats" role="tab" data-toggle="tab"><?= Yii::t('app', 'Match statistics') ?></a></li>
			<li role="presentation"><a href="#scoreboard" aria-controls="scoreboard" role="tab" data-toggle="tab"><?= Yii::t('app', 'Players scoreboard') ?></a></li>
			<li role="presentation"><a href="#weapon-stats" aria-controls="weapon-stats" role="tab" data-toggle="tab"><?= Yii::t('app', 'Weapon statistics') ?></a></li>
			<li role="presentation"><a href="#kill-stats" aria-controls="kill-stats" role="tab" data-toggle="tab"><?= Yii::t('app', 'Kills statistics') ?></a></li>
			<li role="presentation"><a href="#entry-kill-stats" aria-controls="entry-kill-stats" role="tab" data-toggle="tab"><?= Yii::t('app', 'Entry kills') ?></a></li>
			<li role="presentation"><a href="#demos" aria-controls="demos" role="tab" data-toggle="tab"><?= Yii::t('app', 'Demos') ?></a></li>
			<li role="presentation"><a href="#" aria-controls="" role="tab" data-toggle="tab"><?= Yii::t('app', '') ?></a></li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="overview">
				<div class="row">
					<div class="col-sm-6">
						<h5><i class="fa fa-wrench"></i> <b><?= Yii::t('app', 'Match configuration') ?></b></h5>
						<table class="table">
							<tr><th><?= Yii::t('app', 'Config') ?></th><td><?= $model->rules ?></td></tr>
							<tr><th><?= Yii::t('app', 'Max rounds') ?></th><td><?= $model->max_round ?></td></tr>
							<tr><th><?= Yii::t('app', 'Status') ?></th><td><?= $model->max_round ?></td></tr>
							<tr><th><?= Yii::t('app', 'Active') ?></th><td><span class="label label-<?= ($model->enable)? "success":"danger" ?>"><i class="fa fa-flag"></i></span></td></tr>
							<tr><th><?= Yii::t('app', 'Play all rounds') ?></th><td><span class="label label-<?= ($model->config_full_score)? "success":"danger" ?>"><i class="fa fa-flag"></i></span></td></tr>
							<tr><th><?= Yii::t('app', 'Wait for streamers') ?></th><td><span class="label label-<?= ($model->config_streamer)? "success":"danger" ?>"><i class="fa fa-flag"></i></span></td></tr>
							<tr><th><?= Yii::t('app', 'Overtimes enabled') ?></th><td><span class="label label-<?= ($model->config_ot)? "success":"danger" ?>"><i class="fa fa-flag"></i></span></td></tr>
							<tr><th><?= Yii::t('app', 'Knife round') ?></th><td><span class="label label-<?= ($model->config_knife_round)? "success":"danger" ?>"><i class="fa fa-flag"></i></span></td></tr>
							<tr><th><?= Yii::t('app', 'Generate Heatmap') ?></th><td><span class="label label-<?= ($model->config_heatmap)? "success":"danger" ?>"><i class="fa fa-flag"></i></span></td></tr>
						</table>
					</div>
					<div class="col-xs-6">
						<h5><i class="fa fa-server"></i> <b><?= Yii::t('app', 'Match information') ?></b></h5>
						<table class="table">
							<tr>
								<th><?= Yii::t('app', 'Score') ?></th>
								<td>
									<span class="<?= (($model->currentMap->current_side == 'ct')? 'text-primary' : 'text-warning') ?>"><?= (($model->teamA)? $model->teamA->name : $model->team_a_name)?></span> <?= ((strlen($model->team_a_flag) == 2)? '<img src="blank.gif" class="teamflag teamflag-' . strtolower($model->team_a_flag) . '" alt="" /> ' : '')?><span class="<?= (($model->score_a <= $model->score_b)? ($model->score_a == $model->score_b)? '' : 'text-danger' : 'text-success')?>"><?= $model->score_a ?></span> — <span class="<?= (($model->score_b <= $model->score_a)? ($model->score_a == $model->score_b)? '' : 'text-danger' : 'text-success') ?>"><?= $model->score_b ?></span><?= ((strlen($model->team_b_flag) == 2)? ' <img src="blank.gif" class="teamflag teamflag-' . strtolower($model->team_b_flag) . '" alt="" />' : '') ?> <span class="<?= (($model->currentMap->current_side == 'ct')? 'text-warning' : 'text-primary') ?>"><?= (($model->teamB)? $model->teamB->name : $model->team_b_name) ?></span>
								</td>
							</tr>
							<tr><th><?= Yii::t('app', 'Rounds Played') ?></th><td><?= ((int) $model->score_a + (int) $model->score_b) ?></td></tr>
							<tr>
								<th><?= Yii::t('app', 'Number of players') ?></th>
								<td><?
									$a = 0;
									$b = 0;
									$spec = 0;
									foreach ($model->players as $player) {
										if ($player->team == "other")
											$spec++;
										if ($player->team == "a")
											$a++;
										if ($player->team == "b")
											$b++;
									}
									echo count($model->players) . ": " . (($model->teamA)? $model->teamA->name : $model->team_a_name) . " : " . $a . "; " . (($model->teamB)? $model->teamB->name : $model->team_b_name) . " : " . $b . "; ".Yii::t('app', "Spectators")." : " . $spec;
									?>
								</td>
							</tr>
						</table>
						<h5><i class="fa fa-globe"></i> <b><?= Yii::t('app', 'Score details') ?></b></h5>
						<table class="table">
							<tr><th></th><th><?= (($model->teamA)? $model->teamA->name : $model->team_a_name) ?></th><th><?= (($model->teamB)? $model->teamB->name : $model->team_b_name) ?></th></tr>
							<? foreach ($model->currentMap->mapsScores as $score): ?>
								<?
								$score1_side1 = $score->score1_side1;
								$score1_side2 = $score->score1_side2;
								$score2_side1 = $score->score2_side1;
								$score2_side2 = $score->score2_side2;
								?>
								<? if ($score->type_score != "normal"): ?>
									<tr>
										<th colspan="3"><?= Yii::t('app', "Overtime"); ?></th>
									</tr>
								<? endif; ?>
								<tr>
									<th width="200"><?= Yii::t('app', "First Side"); ?></th>
									<td><?php echo $score1_side1; ?></td>
									<td><?php echo $score2_side1; ?></td>
								</tr>
								<tr>
									<th width="200"><?= Yii::t('app', "Second Side"); ?></th>
									<td><?php echo $score1_side2; ?></td>
									<td><?php echo $score2_side2; ?></td>
								</tr>
							<? endforeach; ?>
						</table>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="match-stats">
				<?= $this->render('_match_stats', [
					'match' => $model,
				]) ?>
			</div>
			<div role="tabpanel" class="tab-pane" id="scoreboard">
				<?= $this->render('_scoreboard', [
					'match' => $model,
				]) ?>
			</div>
			<div role="tabpanel" class="tab-pane" id="weapon-stats">
				<?= $this->render('_weapon_stats', [
					'match' => $model,
				]) ?>
			</div>
			<div role="tabpanel" class="tab-pane" id="kill-stats">
				<?= $this->render('_kill_stats', [
					'match' => $model,
				]) ?>
			</div>
			<div role="tabpanel" class="tab-pane" id="entry-kill-stats">
				<?= $this->render('_entry_kills_stats', [
					'match' => $model,
				]) ?>
			</div>
			<div role="tabpanel" class="tab-pane" id="demos">
				<?= $this->render('_demos_stats', [
					'match' => $model,
				]) ?>
			</div>
			<div role="tabpanel" class="tab-pane" id=""></div>
		</div>

	</div>

</div>
