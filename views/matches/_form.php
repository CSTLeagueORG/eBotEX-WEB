<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Matches\Matches */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="matches-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'server_id')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'season_id')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'team_a')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'team_a_flag')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'team_a_name')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'team_b')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'team_b_flag')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'team_b_name')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'status')->textInput() ?>

	<?= $form->field($model, 'is_paused')->textInput() ?>

	<?= $form->field($model, 'score_a')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'score_b')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'max_round')->textInput() ?>

	<?= $form->field($model, 'rules')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'overtime_startmoney')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'overtime_max_round')->textInput() ?>

	<?= $form->field($model, 'config_full_score')->textInput() ?>

	<?= $form->field($model, 'config_ot')->textInput() ?>

	<?= $form->field($model, 'config_streamer')->textInput() ?>

	<?= $form->field($model, 'config_knife_round')->textInput() ?>

	<?= $form->field($model, 'config_switch_auto')->textInput() ?>

	<?= $form->field($model, 'config_auto_change_password')->textInput() ?>

	<?= $form->field($model, 'config_password')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'config_heatmap')->textInput() ?>

	<?= $form->field($model, 'config_authkey')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'enable')->textInput() ?>

	<?= $form->field($model, 'map_selection_mode')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'ingame_enable')->textInput() ?>

	<?= $form->field($model, 'current_map')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'force_zoom_match')->textInput() ?>

	<?= $form->field($model, 'identifier_id')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'startdate')->textInput() ?>

	<?= $form->field($model, 'auto_start')->textInput() ?>

	<?= $form->field($model, 'auto_start_time')->textInput() ?>

	<?= $form->field($model, 'created_at')->textInput() ?>

	<?= $form->field($model, 'updated_at')->textInput() ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
