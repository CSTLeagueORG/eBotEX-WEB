<?php
	use app\models\Matches\RoundSummary;
	use app\models\Stats\PlayerKill;
	use yii\helpers\Html;

	/* @var $this yii\web\View */
	/* @var $match app\models\Matches\Matches */
	$players = array();

	$statsRounds = array();

	$weapons = array();

	$players = array();
	$players2 = array();

	$teams = array();

	$rounds = RoundSummary::find()->where(["match_id" => $match->id])->orderBy("round_id")->all();
	foreach($rounds as $round) {
		/* @var $round app\models\Matches\RoundSummary */
		$kill = PlayerKill::find()->where([
			"match_id" => $match->id, "round_id" => $round->round_id,
		])->orderBy("created_at")->one();
		if($kill) {
			/* @var $kill app\models\Stats\PlayerKill */
			$team = $kill->killer_team == "CT"? "ct" : "t";
			$team_killed = $kill->killed_team == "CT"? "ct" : "t";
			if(!isset($weapons[$kill->weapon])) $weapons[$kill->weapon] = 0;
			$weapons[$kill->weapon]++;
			if($team == "ct" && $round->ct_win || $team == "t" && $round->t_win) {
				@$players[$kill->killer->steamid]['name'] = $kill->killed_name;
				@$players[$kill->killer->steamid]['count']++;
				@$players[$kill->killer->steamid]['weapons'][$kill->weapon]++;

				$statsRounds[$round->round_id] = array("round" => $round, "kill" => $kill, "type" => "win");
			} else {
				@$players[$kill->killer->steamid]['name'] = $kill->killer_name;
				@$players[$kill->killer->steamid]['loose']++;

				$statsRounds[$round->round_id] = array("round" => $round, "kill" => $kill, "type" => "loose");
			}

			@$players[$kill->killer->steamid]['matchs'][$match->id] = $match->score_a + $match->score_b;
			@$players2[$kill->killed->steamid]['matchs'][$match->id] = $match->score_a + $match->score_b;

			if($team_killed == "ct" && $round->t_win || $team_killed == "t" && $round->ct_win) {
				@$players2[$kill->killed->steamid]['name'] = $kill->killed_name;
				@$players2[$kill->killed->steamid]['count']++;
			} else {
				@$players2[$kill->killed->steamid]['name'] = $kill->killed_name;
				@$players2[$kill->killed->steamid]['loose']++;
			}

			$s = $kill->killer->team;
			$name = "";
			if($s == "a") {
				$name = $match->team_a_name;
			} elseif($s == "b") {
				$name = $match->team_b_name;
			}

			if($team == "ct" && $round->ct_win || $team == "t" && $round->t_win) {
				@$teams[$name]['name'] = $name;
				@$teams[$name]['count']++;
			} else {
				@$teams[$name]['name'] = $name;
				@$teams[$name]['loose']++;
			}

			$name = "";
			$s = $kill->killed->team;
			if($s == "a") {
				$name = $match->team_a_name;
			} elseif($s == "b") {
				$name = $match->team_b_name;
			}

			if($team_killed == "ct" && $round->t_win || $team_killed == "t" && $round->ct_win) {
				@$teams2[$name]['name'] = $name;
				@$teams2[$name]['count']++;
			} else {
				@$teams2[$name]['name'] = $name;
				@$teams2[$name]['loose']++;
			}

			@$teams[$name]['matchs'][$match->id] = $match->score_a + $match->score_b;
			@$teams2[$name]['matchs'][$match->id] = $match->score_a + $match->score_b;
		}
	}

	function cmpCount ($a, $b) {
		if(!isset($a['count'])) {
			$a['count'] = 0;
		}
		if(!isset($b['count'])) {
			$b['count'] = 0;
		}
		if($a['count'] == $b['count']) {
			return 0;
		}
		return ($a['count'] > $b['count'])? -1 : 1;
	}

	uasort($players, "cmpCount");
	uasort($players2, "cmpCount");
	arsort($weapons);
?>

<h5><b><i class="fa fa-fire"></i> Entry Kills</b></h5>

<div class="row-fluid">
	<div class="col-md-6">
		<table class="table table-striped">
			<thead>
			<tr>
				<th>Round</th>
				<th>Killer</th>
				<th>Killed</th>
				<th>Weapon</th>
				<th>Result</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach($statsRounds as $stat): ?>
				<tr>
					<td><?php echo $stat['round']->round_id; ?></td>
					<td><?php echo $stat['kill']->killer_name; ?></td>
					<td><?php echo $stat['kill']->killed_name; ?></td>
					<td><?php echo $stat['kill']->weapon; ?> <?= Html::img(Yii::$app->urlManager->createAbsoluteUrl("/images/kills/csgo/" . $stat['kill']->weapon . ".png"), array("class" => "needTips_S")); ?></td>
					<td><?php echo $stat['type']; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<h5><b>By players</b></h5>
		<table class="table table-striped">
			<thead>
			<tr>
				<th><?= Yii::t('app', "Name"); ?></th>
				<th><?= Yii::t('app', "Total"); ?></th>
				<th><?= Yii::t('app', "Round win"); ?></th>
				<th><?= Yii::t('app', "Round lost"); ?></th>
				<th><?= Yii::t('app', "Ratio"); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach($players as $k => $v): ?>
				<tr>
					<td><?php echo $v['name']; ?></td>
					<td><?php echo @$v["count"] + @$v["loose"] * 1; ?></td>
					<td><?php echo @$v["count"] * 1; ?></td>
					<td><?php echo @$v["loose"] * 1; ?></td>
					<td><?php echo round(((@$v["count"]) / (@$v["count"] + @$v["loose"])) * 100, 2); ?>%</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<h5><b>By team</b></h5>
		<table class="table table-striped">
			<thead>
			<tr>
				<th><?= Yii::t('app', "Name"); ?></th>
				<th><?= Yii::t('app', "Total"); ?></th>
				<th><?= Yii::t('app', "Round win"); ?></th>
				<th><?= Yii::t('app', "Round lost"); ?></th>
				<th><?= Yii::t('app', "Ratio"); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach($teams as $k => $v): ?>
				<tr>
					<td><?php echo $v['name']; ?></td>
					<td><?php echo @$v["count"] + @$v["loose"] * 1; ?></td>
					<td><?php echo @$v["count"] * 1; ?></td>
					<td><?php echo @$v["loose"] * 1; ?></td>
					<td><?php echo round(((@$v["count"]) / (@$v["count"] + @$v["loose"])) * 100, 2); ?>%</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>

</div>
