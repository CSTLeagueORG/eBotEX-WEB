<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use \app\models\Matches\Matches;
use \app\models\Teams\Teams;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Matches\MatchesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Matches');
$this->params['breadcrumbs'][] = $this->title;
?>

<? if (!Yii::$app->user->isGuest and  Yii::$app->user->identity->is_admin): ?>
<script>
	function doRequest(event, ip, id, authkey) {
		var data = id + " " + event + " " + ip;
		data = Aes.Ctr.encrypt(data, authkey, 256);
		send = JSON.stringify([data, ip]);
		socket.emit("matchCommandSend", send);
		$('#loading_' + id).show();
		return false;
	}

	var enableNotifScore = false;
	var lastMatchEnd = 0;
	function getButtons(match_id) {
		$.ajax({
			type: "POST",
			url: "/admin.php/matchs/actions/" + match_id,
		}).done(function( msg ) {
			var data = $.parseJSON(msg);
			var output = "";
			for (var i = 0; i < data.length; i++) {
				output += data[i];
			}
			$('#matchs-actions-'+match_id).children('td.matchs-actions-container').empty().append(output);
		});
	}
	$(document).ready(function() {
		PNotify.desktop.permission();
		initSocketIo(function(socket) {
			socket.emit("identify", {type: "matchs"});
			socket.on("matchsHandler", function(data) {
				var data = jQuery.parseJSON(data);
				if (data['content'] == 'stop')
					location.reload();
				else if (data['message'] == 'button') {
					getButtons(data['id']);
					$('#loading_' + data['id']).hide();
				} else if (data['message'] == 'streamerReady') {
					$('.streamer_' + data['id']).addClass('disabled');
					$('#loading_' + data['id']).hide();
				} else if (data['message'] == 'status') {
					if (data['content'] == 'Finished') {
						if (lastMatchEnd != data['id']) {
							new PNotify({
								title: 'Match finished',
								type: 'info',
								text: $("#team_a-"+data['id']).text()+ " vs "+$("#team_b-"+data['id']).text(),
								desktop: {
									desktop: true
								}
							});
						}
						lastMatchEnd = data['id'];
						location.reload();
					} else if (data['content'] == 'is_paused') {
						new PNotify({
							title: 'Match paused!',
							type: 'info',
							text: $("#team_a-"+data['id']).text()+ " vs "+$("#team_b-"+data['id']).text(),
							desktop: {
								desktop: true
							}
						});
						$("#flag-" + data['id']).attr('src', "/images/icons/flag_yellow.png");
						if (getSessionStorageValue('sound') == "on")
							$("#soundHandle").trigger('play');
					} else if (data['content'] == 'is_unpaused') {
						new PNotify({
							title: 'Match unpaused!',
							type: 'info',
							text: $("#team_a-"+data['id']).text()+ " vs "+$("#team_b-"+data['id']).text(),
							desktop: {
								desktop: true
							}
						});
						$("#flag-" + data['id']).attr('src', "/images/icons/flag_green.png");
						if (getSessionStorageValue('sound') == "on")
							$("#soundHandle").trigger('play');
					} else if (data['content'] != 'Starting') {
						if ($("#flag-" + data['id']).attr('src') == "/images/icons/flag_red.png") {
							location.reload();
						} else {
							$("#flag-" + data['id']).attr('src', "/images/icons/flag_green.png");
							$('#loading_' + data['id']).hide();
						}
						$("div.status-" + data['id']).html(data['content']);
					}
				} else if (data['message'] == 'score') {
					if (data['scoreA'] < 10)
						data['scoreA'] = "0" + data['scoreA'];
					if (data['scoreB'] < 10)
						data['scoreB'] = "0" + data['scoreB'];

					if (enableNotifScore) {
						new PNotify({
							title: 'Score Update',
							type: 'info',
							text: $("#team_a-"+data['id']).text()+ " ("+data['scoreA']+") vs ("+data['scoreB']+") "+$("#team_b-"+data['id']).text(),
							desktop: {
								desktop: true
							}
						});
					}

					if (data['scoreA'] == data['scoreB'])
						$("#score-" + data['id']).html("<font color=\"blue\">" + data['scoreA'] + "</font> - <font color=\"blue\">" + data['scoreB'] + "</font>");
					else if (data['scoreA'] > data['scoreB'])
						$("#score-" + data['id']).html("<font color=\"green\">" + data['scoreA'] + "</font> - <font color=\"red\">" + data['scoreB'] + "</font>");
					else if (data['scoreA'] < data['scoreB'])
						$("#score-" + data['id']).html("<font color=\"red\">" + data['scoreA'] + "</font> - <font color=\"green\">" + data['scoreB'] + "</font>");
				} else if (data['message'] == 'teams') {
					if (data['teamA'] == 'ct') {
						$("#team_a-"+data['id']).html("<font color='blue'>"+$("#team_a-"+data['id']).text()+"</font>")
						$("#team_b-"+data['id']).html("<font color='red'>"+$("#team_b-"+data['id']).text()+"</font>")
					} else {
						$("#team_a-"+data['id']).html("<font color='red'>"+$("#team_a-"+data['id']).text()+"</font>")
						$("#team_b-"+data['id']).html("<font color='blue'>"+$("#team_b-"+data['id']).text()+"</font>")
					}
				} else if (data['message'] == 'currentMap') {
					$("#map-"+data['id']).html(data['mapname']);
				}
			});
		});
	});
</script>
	<audio id="soundHandle" style="display: none;"></audio>
	<script>
		function getSessionStorageValue(key) {
			if (sessionStorage) {
				try {
					return sessionStorage.getItem(key);
				} catch (e) {
					return 0;
				}
			}
			return 0;
		}

		function setSessionStorageValue(key, value) {
			if (sessionStorage) {
				try {
					return sessionStorage.setItem(key, value);
				} catch (e) {
				}
			}
		}

		function startMatch(id) {
			$("#match_id").val(id);
			$("#match_start").submit();
			$('#loading_' + id).show();
		}

		var currentMatchAdmin = 0;
		$(function() {
			$(".bo3").popover();

			$.ajax({
				url: "/images/soundHandle/notify.mp3"
			}).done(function(data) {
				$("#soundHandle").attr('src', '/images/soundHandle/notify.mp3');
			});

			if (getSessionStorageValue("current.selected") != 0) {
				var value = getSessionStorageValue("current.selected");
				if ($("[data-id=" + value + "]:first").length == 1) {
					$("[data-id=" + value + "]:first").click();
				} else {
					setSessionStorageValue("current.selected", 0);
				}
			}
		});
	</script>
<? endif ?>


<div class="matches-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a(Yii::t('app', 'Create new match'), ['create'], ['class' => 'btn btn-success']) ?>
	</p>
	<?php Pjax::begin(); ?>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns'      => [
			'id',
			[
				'attribute'      => Yii::t('app', 'Teams and score'),
				'format'         => 'raw',
				'contentOptions' => ['class' => 'text-center'],
				'headerOptions'  => ['class' => 'text-center'],
				'value'          => function (Matches $model) {
					return $this->render('_cell_teams_score', [
						'model' => $model,
					]);
				},
			],
			[
				'attribute' => Yii::t('app', 'Connect link'),
				'format'    => 'raw',
				'value'     => function (Matches $model) {
					return "<a href=\"steam://connect/{$model->ip}/{$model->config_password}\">{$model->server->hostname}</a>";
				},
			],
			[
				'attribute' => Yii::t('app', 'Season'),
				'format'    => 'raw',
				'value'     => function (Matches $model) {
					return ($model->season)? $model->season->name : Yii::t('app', 'Not defined');
				},
			],
			[
				'attribute' => Yii::t('app', 'Status'),
				'format'    => 'raw',
				'value'     => function (Matches $model) {
					$result = '';
					if(($model->status == 0 or $model->status == 13 or !$model->enable) and $model->status != 14) {
						$result = "<div class='label label-danger'><i class='glyphicon glyphicon-flag'></i></div> ";
					} elseif($model->status > 0 and $model->status < 13) {
						$result = ($model->is_paused)? "<div class='label label-warning'><i class='glyphicon glyphicon-flag'></i></div> " : "<div class='label label-success'><i class='glyphicon glyphicon-flag'></i></div> ";
					}
					switch ($model->status) {
						case 0:
							$result .= Yii::t('app', 'Not started');
							break;
						case 1:
							$result .= Yii::t('app', 'Starting');
							break;
						case 2:
							$result .= Yii::t('app', 'Warmup knife');
							break;
						case 3:
							$result .= Yii::t('app', 'Knife round');
							break;
						case 4:
							$result .= Yii::t('app', 'Side picking');
							break;
						case 5:
							$result .= Yii::t('app', 'Warmup 1st side');
							break;
						case 6:
							$result .= Yii::t('app', '1st side');
							break;
						case 7:
							$result .= Yii::t('app', 'Warmup 2nd side');
							break;
						case 8:
							$result .= Yii::t('app', '2nd side');
							break;
						case 9:
							$result .= Yii::t('app', 'OT warmup 1st side');
							break;
						case 10:
							$result .= Yii::t('app', 'OT 1st side');
							break;
						case 11:
							$result .= Yii::t('app', 'OT warmup 2nd side');
							break;
						case 12:
							$result .= Yii::t('app', 'OT 2nd side');
							break;
						case 13:
							$result .= Yii::t('app', 'Match over');
							break;
						case 14:
							$result .= Yii::t('app', 'Archived');//<div class=\'label label-default\'><i class=\'fa fa-archive\'></i></div>
							break;
						default:
							$result .= Yii::t('app', 'Unrecognised');
							break;
					}
					return $result;
				},
			],
			[
				'attribute' => Yii::t('app', 'Actions'),
				'format'    => 'raw',
				'value'     => function (Matches $model) {
					$result = '';
					$url = Url::toRoute(['matches/view', 'id' => (string) $model->id]);
					$result .= "
							<a href='$url'><i class='fa fa-eye'></i></a>
						";
					if($model->status < 13 and !$model->enable) {
						$url = Url::toRoute(['matches/play', 'id' => (string) $model->id]);
						$result .= "
							<a href='$url'><i class='fa fa-play'></i></a>
						";
					}
					if($model->enable and $model->is_paused) {
						$url = Url::toRoute(['matches/unpause', 'id' => (string) $model->id]);
						$result .= "
							<a href='$url'><i class='fa fa-play'></i></a>
						";
					}
					if($model->enable and !$model->ingame_enable) {
						$url = Url::toRoute(['matches/continue', 'id' => (string) $model->id]);
						$result .= "
							<a href='$url'><i class='fa fa-play'></i></a>
						";
					}
					if($model->enable and !$model->is_paused) {
						$url = Url::toRoute(['matches/pause', 'id' => (string) $model->id]);
						$result .= "
							<a href='$url'><i class='fa fa-pause'></i></a>
						";
					}
					if($model->enable) {
						$url = Url::toRoute(['matches/stop', 'id' => (string) $model->id]);
						$result .= "
							<a href='$url'><i class='fa fa-stop'></i></a>
						";
					}
					if(!$model->enable and $model->status < 2) {
						$url = Url::toRoute(['matches/update', 'id' => (string) $model->id]);
						$result .= "
							<a href='$url'><i class='fa fa-edit'></i></a>
						";
					}
					if(!$model->enable and $model->status == 13) {
						$url = Url::toRoute(['matches/archive', 'id' => (string) $model->id]);
						$result .= "
							<a href='$url'><i class='fa fa-archive'></i></a>
						";
					}
					if(!$model->enable) {
						$url = Url::toRoute(['matches/delete', 'id' => (string) $model->id]);
						$result .= "
							<a href='$url'><i class='fa fa-trash'></i></a>
						";
					}
					return $result;
				},
			],

//			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>
	<?php Pjax::end(); ?>
</div>
