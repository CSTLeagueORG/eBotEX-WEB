<?php

	use yii\helpers\Html;

	/* @var $this yii\web\View */
	/* @var $model app\models\Matches\MatchesForm */

	$this->title = Yii::t('app', 'Update {modelClass}: ', [
			'modelClass' => 'Match',
		]) . $match->id;
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Matches'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = ['label' => $match->id, 'url' => ['view', 'id' => $match->id]];
	$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="matches-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
