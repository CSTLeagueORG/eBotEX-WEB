<?php
	/** @var $model \app\models\Matches\Matches */
	use yii\helpers\Url;
?>
<a href='<?= Url::toRoute(['matches/view', 'id' => (string) $model->id]) ?>'><i class='fa fa-eye'></i></a>
<? if(!Yii::$app->user->isGuest and Yii::$app->user->identity->is_admin and ($model->status < 13 and !$model->enable)): ?>
<a href='<?= Url::toRoute(['matches/start', 'id' => (string) $model->id]) ?>'><i class='fa fa-play'></i></a>
<? endif ?>
<?
//					if(!Yii::$app->user->isGuest and Yii::$app->user->identity->is_admin and ($model->enable and $model->is_paused)) {
//						$url = Url::toRoute(['matches/unpause', 'id' => (string) $model->id]);
//						$result .= "
//							<a href='$url'><i class='fa fa-play'></i></a>
//						";
//					}
//					if(!Yii::$app->user->isGuest and Yii::$app->user->identity->is_admin and ($model->enable and !$model->ingame_enable)) {
//						$url = Url::toRoute(['matches/continue', 'id' => (string) $model->id]);
//						$result .= "
//							<a href='$url'><i class='fa fa-play'></i></a>
//						";
//					}
?>
<? if(!Yii::$app->user->isGuest and Yii::$app->user->identity->is_admin and ($model->enable and !$model->is_paused)): ?>
<a onclick="doRequest('pauseunpause', '{$model->ip}', '{$model->id}', '{$model->config_authkey}')"><i class='fa fa-pause'></i></a>
<? endif ?>
<? if(!Yii::$app->user->isGuest and Yii::$app->user->identity->is_admin and ($model->enable)): ?>
<a onclick="doRequest('stop', '{$model->ip}', '{$model->id}', '{$model->config_authkey}')"><i class='fa fa-stop'></i></a>
<? endif ?>
<? if(!Yii::$app->user->isGuest and Yii::$app->user->identity->is_admin and (!$model->enable and $model->status < 2)): ?>
<a href='<?= Url::toRoute(['matches/update', 'id' => (string) $model->id]) ?>'><i class='fa fa-edit'></i></a>
<? endif ?>
<? if(!Yii::$app->user->isGuest and Yii::$app->user->identity->is_admin): ?>
<a href='<?= Url::toRoute(['matches/duplicate', 'id' => (string) $model->id]) ?>'><i class='fa fa-files-o'></i></a>
<? endif ?>
<? if(!Yii::$app->user->isGuest and Yii::$app->user->identity->is_admin and (!$model->enable and $model->status == 13)): ?>
<a href='<?= Url::toRoute(['matches/archive', 'id' => (string) $model->id]) ?>'><i class='fa fa-archive'></i></a>
<? endif ?>
<? if(!Yii::$app->user->isGuest and Yii::$app->user->identity->is_admin and (!$model->enable)): ?>
<a href='<?= Url::toRoute(['matches/delete', 'id' => (string) $model->id]) ?>'><i class='fa fa-trash'></i></a>
<? endif ?>