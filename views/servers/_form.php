<?php

	use yii\helpers\Html;
	use yii\widgets\ActiveForm;

	/* @var $this yii\web\View */
	/* @var $model app\models\Servers */
	/* @var $form yii\widgets\ActiveForm */
?>

<div class="servers-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'hostname')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'rcon')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'tv_ip')->textInput(['maxlength' => true]) ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
