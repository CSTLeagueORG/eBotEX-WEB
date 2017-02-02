<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Teams */

$this->title = Yii::t('app', 'Create Teams');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teams'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teams-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
