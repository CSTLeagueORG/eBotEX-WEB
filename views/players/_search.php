<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PlayersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="players-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

	<?= $form->field($model, 'id') ?>

	<?= $form->field($model, 'match_id') ?>

	<?= $form->field($model, 'map_id') ?>

	<?= $form->field($model, 'player_key') ?>

	<?= $form->field($model, 'team') ?>

	<?php // echo $form->field($model, 'ip') ?>

	<?php // echo $form->field($model, 'steamid') ?>

	<?php // echo $form->field($model, 'first_side') ?>

	<?php // echo $form->field($model, 'current_side') ?>

	<?php // echo $form->field($model, 'pseudo') ?>

	<?php // echo $form->field($model, 'nb_kill') ?>

	<?php // echo $form->field($model, 'assist') ?>

	<?php // echo $form->field($model, 'death') ?>

	<?php // echo $form->field($model, 'point') ?>

	<?php // echo $form->field($model, 'hs') ?>

	<?php // echo $form->field($model, 'defuse') ?>

	<?php // echo $form->field($model, 'bombe') ?>

	<?php // echo $form->field($model, 'tk') ?>

	<?php // echo $form->field($model, 'nb1') ?>

	<?php // echo $form->field($model, 'nb2') ?>

	<?php // echo $form->field($model, 'nb3') ?>

	<?php // echo $form->field($model, 'nb4') ?>

	<?php // echo $form->field($model, 'nb5') ?>

	<?php // echo $form->field($model, 'nb1kill') ?>

	<?php // echo $form->field($model, 'nb2kill') ?>

	<?php // echo $form->field($model, 'nb3kill') ?>

	<?php // echo $form->field($model, 'nb4kill') ?>

	<?php // echo $form->field($model, 'nb5kill') ?>

	<?php // echo $form->field($model, 'pluskill') ?>

	<?php // echo $form->field($model, 'firstkill') ?>

	<?php // echo $form->field($model, 'created_at') ?>

	<?php // echo $form->field($model, 'updated_at') ?>

	<div class="form-group">
		<?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
