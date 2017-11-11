<?php

	/* @var $this yii\web\View */
	/* @var $form yii\bootstrap\ActiveForm */

	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;

	$this->title = 'Login';
	$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>

	<?php
		if(Yii::$app->getSession()->hasFlash('error')) {
			echo '<div class="alert alert-danger">' . Yii::$app->getSession()->getFlash('error') . '</div>';
		}
	?>
	<p class="lead">You can sign in through Steam:</p>
	<?= \nodge\eauth\Widget::widget(['action' => 'main/login']) ?>
</div>
