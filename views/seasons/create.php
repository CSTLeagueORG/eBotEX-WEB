<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Seasons */

$this->title = Yii::t('app', 'Create Seasons');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Seasons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seasons-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
