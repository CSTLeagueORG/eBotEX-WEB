<?php

	use app\models\Matches\Round;
	use \app\models\Stats\PlayerKill;
	use yii\helpers\Html;

	/* @var $this yii\web\View */
	/* @var $match app\models\Matches\Matches */
?>
<h5><b><i class="fa fa-fire"></i> <?= Yii::t('app', "Killer / Killed"); ?></b></h5>

<script>
	$(function () {
		$(".highlight_name").mouseover(
			function () {
				var id = $(this).attr("target");
				$("#player-" + id).addClass("highlight");
			});

		$(".highlight_name").mouseout(
			function () {
				var id = $(this).attr("target");
				$("#player-" + id).removeClass("highlight");
			})
	});
</script>

<style>
	.highlight {
		font-weight: bolder;
	}

	.highlight_name {
		cursor: default;
	}
</style>

<?php
	$players = array();
	$kills = PlayerKill::find()->where(["match_id" => $match->id])->all();
	foreach($kills as $kill) {
		/** @var $kill PlayerKill */
		@$players[$kill->killer_id][$kill->killed_id]++;
	}
?>

<table class="table table-striped table-bordered" style="width: auto;" id="tableKilledKiller">
	<thead>
	<tr>
		<td></td>
		<?php $count = 0; ?>
		<?php foreach($match->currentMap->getPlayers()->orderBy('team')->all() as $player): ?>
			<?php if($player->team == "other") continue; ?>
			<td style="width: 30px; text-align: center;">
				<div class="progress" style="width: 25px; height: 25px;  margin-bottom: 0px; margin: auto; ">
					<div class="progress-bar <?php if($player->team == "b") echo "progress-bar-danger"; ?>"
					     style="width: 100%; line-height: 25px;"><?php echo ++$count; ?></div>
				</div>
			</td>
		<?php endforeach; ?>
	</tr>
	</thead>
	<tbody>
	<?php $count = 0; ?>
	<?php foreach($match->currentMap->getPlayers()->orderBy('team')->all() as $player): ?>
		<?php if($player->team == "other") continue; ?>
		<tr>
			<td style="width: 250px; min-width: 250px;" id="player-<?php echo $player->id; ?>">
				<div class="progress"
				     style="width: 25px; height: 25px; float: left; margin-bottom: 0px; margin-right: 10px;">
					<div class="progress-bar <?php if($player->team == "b") echo "progress-bar-danger"; ?>"
					     style="width: 100%; line-height: 25px;"><?php echo ++$count; ?></div>
				</div>
				<a style="font-size: 11px"
				   href="<?= Yii::$app->urlManager->createAbsoluteUrl("player_stats", array("id" => $player->steamid)); ?>"><?php echo $player->pseudo; ?></a>
			</td>
			<?php foreach($match->currentMap->getPlayers()->orderBy('team')->all() as $player2): ?>
				<?php if($player2->team == "other") continue; ?>
				<td class="highlight_name<?php if(@$players[$player->id][$player2->id] * 1 == 0) echo ' text-muted'; ?>"
				    player="<?php echo $player->id; ?>" target="<?php echo $player2->id; ?>"
				    style="text-align: center;"><?php echo @$players[$player->id][$player2->id] * 1; ?></td>
			<?php endforeach; ?>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
