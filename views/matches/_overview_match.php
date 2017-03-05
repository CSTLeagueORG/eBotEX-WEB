<?php

/* @var $this yii\web\View */
/* @var $match app\models\Matches\Matches */
?>
<div class="row">
	<div class="col-sm-6">
		<h5><i class="fa fa-wrench"></i> <b><?= Yii::t('app', 'Match configuration') ?></b></h5>
		<table class="table">
			<tr>
				<th><?= Yii::t('app', 'Config') ?></th>
				<td><?= $match->rules ?></td>
			</tr>
			<tr>
				<th><?= Yii::t('app', 'Max rounds') ?></th>
				<td><?= $match->max_round ?></td>
			</tr>
			<tr>
				<th><?= Yii::t('app', 'Status') ?></th>
				<td><?= $match->max_round ?></td>
			</tr>
			<tr>
				<th><?= Yii::t('app', 'Active') ?></th>
				<td><span class="label label-<?= ($match->enable)? "success" : "danger" ?>"><i
							class="fa fa-flag"></i></span></td>
			</tr>
			<tr>
				<th><?= Yii::t('app', 'Play all rounds') ?></th>
				<td>
									<span class="label label-<?= ($match->config_full_score)? "success" : "danger" ?>"><i
											class="fa fa-flag"></i></span></td>
			</tr>
			<tr>
				<th><?= Yii::t('app', 'Wait for streamers') ?></th>
				<td><span class="label label-<?= ($match->config_streamer)? "success" : "danger" ?>"><i
							class="fa fa-flag"></i></span></td>
			</tr>
			<tr>
				<th><?= Yii::t('app', 'Overtimes enabled') ?></th>
				<td><span class="label label-<?= ($match->config_ot)? "success" : "danger" ?>"><i
							class="fa fa-flag"></i></span></td>
			</tr>
			<tr>
				<th><?= Yii::t('app', 'Knife round') ?></th>
				<td><span class="label label-<?= ($match->config_knife_round)? "success" : "danger" ?>"><i
							class="fa fa-flag"></i></span></td>
			</tr>
			<tr>
				<th><?= Yii::t('app', 'Generate Heatmap') ?></th>
				<td><span class="label label-<?= ($match->config_heatmap)? "success" : "danger" ?>"><i
							class="fa fa-flag"></i></span></td>
			</tr>
		</table>
	</div>
	<div class="col-xs-6">
		<h5><i class="fa fa-server"></i> <b><?= Yii::t('app', 'Match information') ?></b></h5>
		<table class="table">
			<tr>
				<th><?= Yii::t('app', 'Score') ?></th>
				<td>
					<span class="<?= (($match->currentMap->current_side == 'ct')? 'text-primary' : 'text-warning') ?>"><?= (($match->teamA)? $match->teamA->name : $match->team_a_name) ?></span> <?= ((strlen($match->team_a_flag) == 2)? '<i class="teamflag teamflag-' . strtolower($match->team_a_flag) . '"></i> ' : '') ?>
					<span class="<?= (($match->score_a <= $match->score_b)? ($match->score_a == $match->score_b)? '' : 'text-danger' : 'text-success') ?>"><?= $match->score_a ?></span> —
					<span class="<?= (($match->score_b <= $match->score_a)? ($match->score_a == $match->score_b)? '' : 'text-danger' : 'text-success') ?>"><?= $match->score_b ?></span><?= ((strlen($match->team_b_flag) == 2)? ' <i class="teamflag teamflag-' . strtolower($match->team_b_flag) . '"></i>' : '') ?>
					<span class="<?= (($match->currentMap->current_side == 'ct')? 'text-warning' : 'text-primary') ?>"><?= (($match->teamB)? $match->teamB->name : $match->team_b_name) ?></span>
				</td>
			</tr>
			<tr>
				<th><?= Yii::t('app', 'Rounds Played') ?></th>
				<td><?= ((int) $match->score_a + (int) $match->score_b) ?></td>
			</tr>
			<tr>
				<th><?= Yii::t('app', 'Number of players') ?></th>
				<td><?
					$a = 0;
					$b = 0;
					$spec = 0;
					foreach($match->players as $player) {
						if($player->team == "other") {
							$spec++;
						}
						if($player->team == "a") {
							$a++;
						}
						if($player->team == "b") {
							$b++;
						}
					}
					echo count($match->players) . ": " . (($match->teamA)? $match->teamA->name : $match->team_a_name) . " : " . $a . "; " . (($match->teamB)? $match->teamB->name : $match->team_b_name) . " : " . $b . "; " . Yii::t('app', "Spectators") . " : " . $spec;
					?>
				</td>
			</tr>
		</table>
		<h5><i class="fa fa-globe"></i> <b><?= Yii::t('app', 'Score details') ?></b></h5>
		<table class="table">
			<tr>
				<th></th>
				<th><?= (($match->teamA)? $match->teamA->name : $match->team_a_name) ?></th>
				<th><?= (($match->teamB)? $match->teamB->name : $match->team_b_name) ?></th>
			</tr>
			<? foreach($match->currentMap->mapsScores as $score): ?>
				<?
				$score1_side1 = $score->score1_side1;
				$score1_side2 = $score->score1_side2;
				$score2_side1 = $score->score2_side1;
				$score2_side2 = $score->score2_side2;
				?>
				<? if($score->type_score != "normal"): ?>
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