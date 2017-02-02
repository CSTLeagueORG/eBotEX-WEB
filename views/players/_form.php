<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Players */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="players-form">

	<?php $form = ActiveForm::begin(); ?>

	    <?= $form->field($model, 'match_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'map_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'player_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'team')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'steamid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'first_side')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'current_side')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pseudo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nb_kill')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'assist')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'death')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'point')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'defuse')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bombe')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nb1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nb2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nb3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nb4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nb5')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nb1kill')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nb2kill')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nb3kill')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nb4kill')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nb5kill')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pluskill')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'firstkill')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
