<?php

/* @var $this yii\web\View */
/* @var $match app\models\Matches\Matches */
?>
<table class="table table-striped">
	<thead>
	<th width="20"><?= Yii::t('app', "#ID"); ?></th>
	<th width="200"><?= Yii::t('app', "Team 1"); ?></th>
	<th width="80" style="text-align: center;"><?= Yii::t('app', "Score"); ?></th>
	<th width="200" style="text-align: right;"><?= Yii::t('app', "Team 2"); ?></th>
	<th width="150"><?= Yii::t('app', "Map"); ?></th>
	<th width="250"><?= Yii::t('app', "Season"); ?></th>
	<th><?= Yii::t('app', "Size"); ?></th>
	<th></th>
	</thead>
	<tbody>
	<?php $noentry = true; ?>
	<?php foreach($match->maps as $index => $map): ?>
		<?php
		/* @var $map app\models\Matches\Maps */
		$mapScore = $map->mapsScores;
		$score1 = $mapScore[$index]->score1_side1 + $mapScore[$index]->score1_side2;
		$score2 = $mapScore[$index]->score2_side1 + $mapScore[$index]->score2_side2;

		$team1 = $match->teamA? $match->teamA->name : $match->team_a_name;
		$team1_flag = $match->teamA? "<i class='teamflag teamflag-" . strtolower($match->teamA->flag) . "'></i>" : "<i class='teamflag teamflag-" . strtolower($match->team_a_flag) . "'></i>";

		$team2 = $match->teamB? $match->teamB->name : $match->team_b_name;
		$team2_flag = $match->teamB? "<i class='teamflag teamflag-" . strtolower($match->teamB->flag) . "'></i>" : "<i class='teamflag teamflag-" . strtolower($match->team_b_flag) . "'></i>";
		?>
		<?php $demo_file = Yii::$app->params["demo_path"] . DIRECTORY_SEPARATOR . $map->tv_record_file . ".dem.zip"; ?>
		<?php if(file_exists($demo_file)): ?>
			<tr>
				<td>#<?php echo $map->id; ?></td>
				<td><span style="float:left"><?php echo $team1_flag . " " . $team1; ?></span></td>
				<td><span style="text-align: center;"><?php echo $score1 . " - " . $score2; ?></span></td>
				<td><span style="float:right; text-align:right;"><?php echo $team2 . " " . $team2_flag; ?></span></td>
				<td><?php echo $map->map_name; ?></td>
				<td><?php echo $match->season->name; ?></td>
				<td><?php echo round((filesize($demo_file) / 1048576), 2); ?> MB</td>
				<td><a href="#">
						<button class="btn btn-primary"><?= Yii::t('app', "Download Demo"); ?></button>
					</a></td>
			</tr>
			<?php $noentry = false; ?>
		<?php endif; ?>
	<?php endforeach; ?>
	<?php if($noentry): ?>
		<tr>
			<td colspan="8"><?= Yii::t('app', "There are currently no Demofiles available."); ?></td>
		</tr>
	<?php endif; ?>
	</tbody>
</table>
