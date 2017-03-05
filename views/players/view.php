<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Players */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Players'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="players-view">

	<h1><?= Html::encode($this->title) ?></h1>

	<? /*= DetailView::widget([
	             'model' => $model,
	             'attributes' => [
	            'id',
            'match_id',
            'map_id',
            'player_key',
            'team',
            'ip',
            'steamid',
            'first_side',
            'current_side',
            'pseudo',
            'nb_kill',
            'assist',
            'death',
            'point',
            'hs',
            'defuse',
            'bombe',
            'tk',
            'nb1',
            'nb2',
            'nb3',
            'nb4',
            'nb5',
            'nb1kill',
            'nb2kill',
            'nb3kill',
            'nb4kill',
            'nb5kill',
            'pluskill',
            'firstkill',
            'created_at',
            'updated_at',
	             ],
	             ]) */ ?>

</div>
