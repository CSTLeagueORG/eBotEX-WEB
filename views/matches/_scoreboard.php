<?php

/* @var $this yii\web\View */
/* @var $match app\models\Matches */
?>
<script>
	$(function() {
		if ($("#tablePlayers").find("tbody").find("tr").size() > 0)
			$("#tablePlayers").tablesorter({sortList: [[2,1] ]});
	});
</script>
<style>
	.header {
		cursor: pointer;
	}
</style>

<h5><i class="icon-fire"></i> <?= Yii::t('app', "Player Statistics"); ?></h5>

<table class="table table-striped table-condensed" id="tablePlayers">
	<thead>
	<tr>
		<th><?= Yii::t('app', "Team"); ?></th>
		<th><?= Yii::t('app', "Player"); ?></th>
		<th style="border-left: 1px solid #DDDDDD;"><?= Yii::t('app', "Kill"); ?></th>
		<th><?= Yii::t('app', "Assist"); ?></th>
		<th><?= Yii::t('app', "Death"); ?></th>
		<th><?= Yii::t('app', "K/D Rate"); ?></th>
		<th><?= Yii::t('app', "Points"); ?></th>
		<th><?= Yii::t('app', "HeadShot"); ?></th>
		<th><?= Yii::t('app', "HS Rate"); ?></th>
		<th style="border-left: 1px solid #DDDDDD;"><?= Yii::t('app', "Defuse"); ?></th>
		<th><?= Yii::t('app', "Bombe"); ?></th>
		<th><?= Yii::t('app', "TK"); ?></th>
		<th style="border-left: 1px solid #DDDDDD;"><?= Yii::t('app', "1v1"); ?></th>
		<th><?= Yii::t('app', "1v2"); ?></th>
		<th><?= Yii::t('app', "1v3"); ?></th>
		<th><?= Yii::t('app', "1v4"); ?></th>
		<th><?= Yii::t('app', "1v5"); ?></th>
		<th style="border-left: 1px solid #DDDDDD;" class="needTips_S" title="1 kill / round"><?= Yii::t('app', "1K"); ?></th>
		<th class="needTips_S" title="2 kill / round"><?= Yii::t('app', "2K"); ?></th>
		<th class="needTips_S" title="3 kill / round"><?= Yii::t('app', "3K"); ?></th>
		<th class="needTips_S" title="4 kill / round"><?= Yii::t('app', "4K"); ?></th>
		<th class="needTips_S" title="5 kill / round"><?= Yii::t('app', "5K"); ?></th>
		<th style="border-left: 1px solid #DDDDDD;" class="needTips_S" title="First Kill"><?= Yii::t('app', "FK"); ?></th>
		<th><?= Yii::t('app', "Pt Clutch"); ?></th>
	</tr>
	</thead>
	<?php
	$total = array("kill" => 0, "death" => 0, "hs" => 0, "bombe" => 0,
	               "defuse" => 0, "tk" => 0, "point" => 0, "firstkill" => 0,
	               "1v1" => 0, "1v2" => 0, "1v3" => 0, "1v4" => 0, "1v5" => 0,
	               "1kill" => 0, "2kill" => 0, "3kill" => 0, "4kill" => 0, "5kill" => 0, "clutch" => 0, "assist" => 0
	);
	?>
	<tbody>
	<?php foreach ($match->currentMap->players as $player): ?>
		<?php if ($player->team == "other") continue; ?>
		<?php
		$total['kill'] += (int) $player->nb_kill;
		$total['assist']+= (int) $player->assist;
		$total['death']+= (int) $player->death;
		$total['hs']+= (int) $player->hs;
		$total['bombe']+= (int) $player->bombe;
		$total['defuse']+= (int) $player->defuse;
		$total['tk']+= (int) $player->tk;
		$total['point']+= (int) $player->point;
		$total['firstkill']+= (int) $player->firstkill;
		$total['1v1']+= (int) $player->nb1;
		$total['1v2']+= (int) $player->nb2;
		$total['1v3']+= (int) $player->nb3;
		$total['1v4']+= (int) $player->nb4;
		$total['1v5']+= (int) $player->nb5;
		$total['1kill']+= (int) $player->nb1kill;
		$total['2kill']+= (int) $player->nb2kill;
		$total['3kill']+= (int) $player->nb3kill;
		$total['4kill']+= (int) $player->nb4kill;
		$total['5kill']+= (int) $player->nb5kill;

		$clutch = 0;
		$clutch+= 1 * $player->nb1;
		$clutch+= 2 * $player->nb2;
		$clutch+= 3 * $player->nb3;
		$clutch+= 4 * $player->nb4;
		$clutch+= 5 * $player->nb5;
		?>
		<tr>
			<td>
				<?php if ($player->team == "a"): ?>
					<?php echo $match->teamA ? $match->teamA->name : $match->team_a_name; ?>
				<?php elseif ($player->team == "b"): ?>
					<?php echo $match->teamB ? $match->teamB->name : $match->team_b_name; ?>
				<?php endif; ?>
			</td>
			<td><a href="<?//php echo url_for("player_stats", array("id" => $player->getSteamid())); ?>"><?php echo $player->pseudo; ?></a></td>
			<td <?php if ($player->nb_kill == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $player->nb_kill; ?></td>
			<td <?php if ($player->assist == 0) echo 'class="muted" '; ?>><?php echo $player->assist; ?></td>
			<td <?php if ($player->death == 0) echo 'class="muted" '; ?>><?php echo $player->death; ?></td>
			<td <?php if ($player->death == 0) echo 'class="muted" '; ?>><?php if ($player->death == 0) echo $player->nb_kill; else echo round($player->nb_kill / $player->death, 2); ?></td>
			<td <?php if ($player->point == 0) echo 'class="muted" '; ?>><?php echo $player->point; ?></td>
			<td <?php if ($player->hs == 0) echo 'class="muted" '; ?>><?php echo $player->hs; ?></td>
			<td><?php if ($player->nb_kill == 0) echo "0"; else echo round($player->hs / $player->nb_kill, 4) * 100; ?>%</td>
			<td <?php if ($player->defuse == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $player->defuse; ?></td>
			<td <?php if ($player->bombe == 0) echo 'class="muted" '; ?>><?php echo $player->bombe; ?></td>
			<td <?php if ($player->tk == 0) echo 'class="muted" '; ?>><?php echo $player->tk; ?></td>
			<td <?php if ($player->nb1 == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $player->nb1; ?></td>
			<td <?php if ($player->nb2 == 0) echo 'class="muted" '; ?>><?php echo $player->nb2; ?></td>
			<td <?php if ($player->nb3 == 0) echo 'class="muted" '; ?>><?php echo $player->nb3; ?></td>
			<td <?php if ($player->nb4 == 0) echo 'class="muted" '; ?>><?php echo $player->nb4; ?></td>
			<td <?php if ($player->nb5 == 0) echo 'class="muted" '; ?>><?php echo $player->nb5; ?></td>
			<td <?php if ($player->nb1kill == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $player->nb1kill; ?></td>
			<td <?php if ($player->nb2kill == 0) echo 'class="muted" '; ?>><?php echo $player->nb2kill; ?></td>
			<td <?php if ($player->nb3kill == 0) echo 'class="muted" '; ?>><?php echo $player->nb3kill; ?></td>
			<td <?php if ($player->nb4kill == 0) echo 'class="muted" '; ?>><?php echo $player->nb4kill; ?></td>
			<td <?php if ($player->nb5kill == 0) echo 'class="muted" '; ?>><?php echo $player->nb5kill; ?></td>
			<td <?php if ($player->firstkill == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $player->firstkill; ?></td>
			<td <?php if ($clutch == 0) echo 'class="muted" '; ?>><?php echo $clutch; ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	<tfoot>
	<tr>
		<th colspan="2"><?= Yii::t('app', "Total"); ?></th>
		<td><?php echo $total["kill"]; ?></td>
		<td><?php echo $total["assist"]; ?></td>
		<td><?php echo $total["death"]; ?></td>
		<td></td>
		<td><?php echo $total["point"]; ?></td>
		<td><?php echo $total["hs"]; ?></td>
		<td><?php echo @round($total["hs"] / $total["kill"], 4) * 100; ?>%</td>
		<td><?php echo $total["defuse"]; ?></td>
		<td><?php echo $total["bombe"]; ?></td>
		<td><?php echo $total["tk"]; ?></td>
		<td><?php echo $total["1v1"]; ?></td>
		<td><?php echo $total["1v2"]; ?></td>
		<td><?php echo $total["1v3"]; ?></td>
		<td><?php echo $total["1v4"]; ?></td>
		<td><?php echo $total["1v5"]; ?></td>
		<td><?php echo $total["1kill"]; ?></td>
		<td><?php echo $total["2kill"]; ?></td>
		<td><?php echo $total["3kill"]; ?></td>
		<td><?php echo $total["4kill"]; ?></td>
		<td><?php echo $total["5kill"]; ?></td>
		<td></td>
		<td></td>
	</tr>
	</tfoot>
</table>

<h5><i class="icon-info-sign"></i> <?= Yii::t('app', "Info"); ?></h5>
<div class="well">
	<?= Yii::t('app', "<p>Vous pouvez trier tous les champs du tableau pour obtenir des résultats personallisés.</p>
			<p>Les colonnes <b>1K</b>, <b>2K</b>, ... représentent le nombre de kill par round effectué. Par exemple, si j'ai 2 dans la colonne 3K, cela veut dire que j'ai fais 2 rounds où j'ai fais 3 kills.
			<p>La colonne <b>FK</b> signifie <b>First Kill</b>, utile pour voir les personnes qui font les premiers kills</p>
			<p>Les points clutchs représentent si la personne a réalisé plusieurs \"clutch\", par exemple, gagné un 1v1. Ils sont calculés comme ceci: nombre de 1 v X gagné multiplé par X. Si j'ai fais trois 1v1 et un 1v2, j'aurai donc 5 points. (1v1 x 3 = 3, 1v2 x 1 = 2)</p>
"); ?>
</div>
