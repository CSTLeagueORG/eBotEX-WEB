<?php

	use yii\helpers\Html;
	use yii\widgets\ActiveForm;

	/* @var $this yii\web\View */
	/* @var $model app\models\SeasonsSearch */
	/* @var $form yii\widgets\ActiveForm */
?>

<div class="seasons-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

	<?= $form->field($model, 'id') ?>

	<?= $form->field($model, 'name') ?>

	<?= $form->field($model, 'event') ?>

	<?= $form->field($model, 'start') ?>

	<?= $form->field($model, 'end') ?>

	<?php // echo $form->field($model, 'link') ?>

	<?php // echo $form->field($model, 'logo') ?>

	<?php // echo $form->field($model, 'active') ?>

	<?php // echo $form->field($model, 'created_at') ?>

	<?php // echo $form->field($model, 'updated_at') ?>

	<div class="form-group">
		<?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
