<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Matches\MatchesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="matches-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

	<?= $form->field($model, 'id') ?>

	<?= $form->field($model, 'ip') ?>

	<?= $form->field($model, 'server_id') ?>

	<?= $form->field($model, 'season_id') ?>

	<?= $form->field($model, 'team_a') ?>

	<?php // echo $form->field($model, 'team_a_flag') ?>

	<?php // echo $form->field($model, 'team_a_name') ?>

	<?php // echo $form->field($model, 'team_b') ?>

	<?php // echo $form->field($model, 'team_b_flag') ?>

	<?php // echo $form->field($model, 'team_b_name') ?>

	<?php // echo $form->field($model, 'status') ?>

	<?php // echo $form->field($model, 'is_paused') ?>

	<?php // echo $form->field($model, 'score_a') ?>

	<?php // echo $form->field($model, 'score_b') ?>

	<?php // echo $form->field($model, 'max_round') ?>

	<?php // echo $form->field($model, 'rules') ?>

	<?php // echo $form->field($model, 'overtime_startmoney') ?>

	<?php // echo $form->field($model, 'overtime_max_round') ?>

	<?php // echo $form->field($model, 'config_full_score') ?>

	<?php // echo $form->field($model, 'config_ot') ?>

	<?php // echo $form->field($model, 'config_streamer') ?>

	<?php // echo $form->field($model, 'config_knife_round') ?>

	<?php // echo $form->field($model, 'config_switch_auto') ?>

	<?php // echo $form->field($model, 'config_auto_change_password') ?>

	<?php // echo $form->field($model, 'config_password') ?>

	<?php // echo $form->field($model, 'config_heatmap') ?>

	<?php // echo $form->field($model, 'config_authkey') ?>

	<?php // echo $form->field($model, 'enable') ?>

	<?php // echo $form->field($model, 'map_selection_mode') ?>

	<?php // echo $form->field($model, 'ingame_enable') ?>

	<?php // echo $form->field($model, 'current_map') ?>

	<?php // echo $form->field($model, 'force_zoom_match') ?>

	<?php // echo $form->field($model, 'identifier_id') ?>

	<?php // echo $form->field($model, 'startdate') ?>

	<?php // echo $form->field($model, 'auto_start') ?>

	<?php // echo $form->field($model, 'auto_start_time') ?>

	<?php // echo $form->field($model, 'created_at') ?>

	<?php // echo $form->field($model, 'updated_at') ?>

	<div class="form-group">
		<?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
