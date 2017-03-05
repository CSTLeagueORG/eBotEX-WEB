<?php

use yii\bootstrap\Html;
use kartik\tabs\TabsX;


/* @var $this yii\web\View */
/* @var $model app\models\Matches\Matches */

$this->title = Yii::t('app', 'Match #') . $model->id . " " . (($model->teamA)? $model->teamA->name : $model->team_a_name) . ' vs ' . (($model->teamB)? $model->teamB->name : $model->team_b_name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Matches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<script>
	$(function () {
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
		<?= TabsX::widget([
				'items' => [
						[
							'label'       => Yii::t('app', 'Overview'),
							'content'     => $this->render('_overview_match', [
								'match' => $model,
							]),
							'options' => ['id' => 'overview'],
							'active'      => true,
						],
						[
							'label'       => Yii::t('app', 'Match statistics'),
							'content'     => $this->render('_match_stats', [
								'match' => $model,
							]),
							'options' => ['id' => 'match-stats'],
						],
						[
							'label'       => Yii::t('app', 'Players scoreboard'),
							'content'     => $this->render('_scoreboard', [
								'match' => $model,
							]),
							'options' => ['id' => 'scoreboard'],
						],
						[
							'label'       => Yii::t('app', 'Weapon statistics'),
							'content'     => $this->render('_weapon_stats', [
								'match' => $model,
							]),
							'options' => ['id' => 'weapon-stats'],
						],
						[
							'label'       => Yii::t('app', 'Kills statistics'),
							'content'     => $this->render('_kill_stats', [
								'match' => $model,
							]),
							'options' => ['id' => 'kill-stats'],
						],
						[
							'label'       => Yii::t('app', 'Entry kills'),
							'content'     => $this->render('_entry_kills_stats', [
								'match' => $model,
							]),
							'options' => ['id' => 'entry-kills-stats'],
						],
						[
							'label'       => Yii::t('app', 'Demos'),
							'content'     => $this->render('_demos_stats', [
								'match' => $model,
							]),
							'options' => ['id' => 'demos-stats'],
						],
				],
				'enableStickyTabs' => true,
				'stickyTabsOptions' => [
					'backToTop' => true,
				],
		]) ?>

	</div>

</div>
