<?php

use \app\models\Stats\PlayerKill;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $match app\models\Matches\Matches */
?>
<h5><b><i class="fa fa-fire"></i> <?= Yii::t('app',"Weapon Statistics by Player"); ?></b></h5>

<?php
$players = array();
$kills = PlayerKill::find()->where(["match_id" => $match->id])->all();
foreach ($kills as $kill) {
	/** @var $kill PlayerKill */
	@$players[$kill->killer_id][$kill->weapon]["k"]++;
	@$players[$kill->killed_id][$kill->weapon]["d"]++;
}

$weapons = array("knife_default_ct", "knife_default_t", "taser", "hkp2000", "usp_silencer", "usp_silencer_off", "glock", "deagle", "p250", "tec9", "fiveseven", "awp", "m4a1", "m4a1_silencer", "m4a1_silencer_off", "ak47", "famas", "galilar", "hegrenade", "inferno", "scar20", "mp7", "bizon", "p90", "mag7", "ump45", "taser", "nova", "mac10", "mp9", "elite", "ssg08");
?>

<table class="table">
	<thead>
	<tr>
		<td rowspan="2"></td>
		<?php foreach ($weapons as $weapon): ?>
			<td style="border-left: 1px solid #DDDDDD; text-align: center; min-width: 50px;" colspan="2"><?= Html::img(Yii::$app->urlManager->createAbsoluteUrl("/images/kills/csgo/" . $weapon . ".png"), array("class" => "needTips_S", "title" => $weapon)); ?></td>
		<?php endforeach; ?>
	</tr>
	<tr>
		<?php foreach ($weapons as $weapon): ?>
			<td style="border-left: 2px solid #DDDDDD;text-align: center;font-size: 10px; border-right: 1px solid #EEEEEE;">K</td>
			<td style="font-size: 10px; text-align: center;">D</td>
		<?php endforeach; ?>
	</tr>
	</thead>
	<tbody>
	<?php $weaponsStats = array(); ?>
	<?php foreach ($match->currentMap->getPlayers()->orderBy('team')->all() as $player): ?>
		<?php if ($player->team == "other") continue; ?>
		<tr>
			<td style="width: 150px; min-width: 150px;"><a href="<?= Yii::$app->urlManager->createAbsoluteUrl("player_stats", array("id" => $player->steamid)); ?>"><?php echo $player->pseudo; ?></a></td>
			<?php foreach ($weapons as $weapon): ?>
				<td style="text-align: center;border-left: 2px solid #DDDDDD; border-right: 1px solid #EEEEEE;" <?php if (@$players[$player->id][$weapon]["k"] * 1 == 0) echo 'class="text-muted"'; ?>>
					<?php echo @$players[$player->id][$weapon]["k"] * 1; ?>
				</td>
				<td style="text-align: center;" <?php if (@$players[$player->id][$weapon]["d"] * 1 == 0) echo 'class="text-muted"'; ?>>
					<?php echo @$players[$player->id][$weapon]["d"] * 1; ?>
				</td>
				<?php @$weaponsStats[$weapon] += @$players[$player->id][$weapon]["k"] * 1; ?>
			<?php endforeach; ?>
		</tr>
	<?php endforeach; ?>
	</tbody>
	<tfoot>
	<tr>
		<td>Total</td>
		<?php foreach ($weapons as $weapon): ?>
			<td <?php if ($weaponsStats[$weapon] * 1 == 0) echo 'class="text-muted" '; ?> style="border-left: 2px solid #DDDDDD; text-align: center;" colspan="2"><?php echo $weaponsStats[$weapon] * 1; ?></td>
		<?php endforeach; ?>
	</tr>
	</tfoot>
</table>

<h5><i class="icon-info-sign"></i> <?= Yii::t('app',"Info"); ?></h5>
<div class="well">
	<?= Yii::t('app',"The column <b>K</b> represents the number of kill with this weapons, the column <b>D</b> represents the number of times the player has been killed by the weapon"); ?>
</div>