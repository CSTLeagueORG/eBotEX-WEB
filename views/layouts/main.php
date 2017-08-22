<?php

	/* @var $this \yii\web\View */
	/* @var $content string */

	use yii\helpers\Html;
	use yii\bootstrap\Nav;
	use yii\bootstrap\Alert;
	use yii\bootstrap\NavBar;
	use yii\widgets\Breadcrumbs;
	use app\assets\AppAsset;

	AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>
<script>
	function submitForm(id) {
		$("#" + id).submit();
	}

	var socketIoAddress = "<?php echo Yii::$app->params['bot_ip'] ?>:<?php echo Yii::$app->params["bot_port"]; ?>";
	var socket = null;
	var socketIoLoaded = false;
	var loadingSocketIo = false;
	var callbacks = new Array();
	function initSocketIo(callback) {
		callbacks.push(callback);
		if (loadingSocketIo) {
			return;
		}

		if (socketIoLoaded) {
			if (typeof callback == "function") {
				callback(socket);
			}
			return;
		}

		loadingSocketIo = true;
		$.getScript("<?php echo Yii::$app->params['ssl_enabled'] ? "https" : "http" ?>://" + socketIoAddress + "/socket.io/socket.io.js", function () {
			socket = io.connect("<?php echo Yii::$app->params['ssl_enabled'] ? "https" : "http" ?>://" + socketIoAddress);
			socket.on('connect', function () {
				socketIoLoaded = true;
				loadingSocketIo = false;
				if (typeof callback == "function") {
					callback(socket);
				}
				for (var c in callbacks) {
					callbacks[c](socket);
				}
				//callbacks = new Array();
			});
		});
	}
</script>
<?php $this->beginBody() ?>

<div class="wrap">
	<?php
		NavBar::begin([
			'brandLabel'            => 'eBotEX',
			'brandUrl'              => Yii::$app->homeUrl,
			'options'               => [
				'class' => 'navbar-inverse navbar-fixed-top',
			],
			'innerContainerOptions' => ['class' => 'container-fluid'],
		]);
		echo Nav::widget([
			'options' => ['class' => 'navbar-nav navbar-right'],
			'items'   => [
				['label' => 'Home', 'url' => ['/main/index']],
				['label' => 'About', 'url' => null, 'linkOptions' => ['href' => 'https://tools.cstleague.org/ebotex']],
				['label'       => 'Documentation', 'url' => null,
				 'linkOptions' => ['href' => 'https://tools.cstleague.org/ebotex/docs'],
				],
				Yii::$app->user->isGuest? (
				['label' => 'Login', 'url' => ['/main/login']]
				) : (
					'<li>'
					. Html::beginForm(['/main/logout'], 'post')
					. Html::submitButton(
						'Logout (' . Yii::$app->user->identity->nickname . ')',
						['class' => 'btn btn-link logout']
					)
					. Html::endForm()
					. '</li>'
				),
			],
		]);
		NavBar::end();
	?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-2">
				<div class="panel panel-default">
					<div class="panel-body">
						<script>
							function goToMatch() {
								var id = $("#match_id_go").val();
								if (id > 0)
									document.location.href = "<?= Yii::$app->urlManager->createAbsoluteUrl("/matches/view/"); ?>" + id;
							}

							function getSessionStorageValue(key) {
								if (sessionStorage) {
									try {
										return sessionStorage.getItem(key);
									} catch (e) {
										return null;
									}
								}
								return null;
							}

							function setSessionStorageValue(key, value) {
								if (sessionStorage) {
									try {
										return sessionStorage.setItem(key, value);
									} catch (e) {
									}
								}
							}

							function ebotSound(parameter) {
								if (parameter == "on") {
									$('div#ebotSound').html('<font color="green"><b>Sound On <a href="#" onclick="ebotSound(\'off\');">(Turn Off)</a></b></font>');
									setSessionStorageValue('sound', 'on');
								}
								else if (parameter == "off") {
									$('div#ebotSound').html('<font color="red"><b>Sound Off <a href="#" onclick="ebotSound(\'on\');">(Turn On)</a></b></font>');
									setSessionStorageValue('sound', 'off');
								}
							}

							$(document).ready(function () {
								initSocketIo(function (socket) {
									$('div#websocketAlive').html('<font color="green"><b>WebSocket online</b></font>');
									socket.on('connect', function () {
										$('div#websocketAlive').html('<font color="green"><b>WebSocket online</b></font>');
									});
									socket.emit("identify", {type: "alive"});
									socket.on("aliveHandler", function (data) {
										if (data.data == "__isAlive__") {
											$('div#ebotAlive').html('<font color="green"><b>eBot online</b></font>');
										}
									});
									socket.on('disconnect', function () {
										$('div#websocketAlive').html('<font color="red"><b>WebSocket offline</b></font>');
										$('div#ebotAlive').html('<font color="red"><b>eBot offline</b></font>');
									});
								});

								if (getSessionStorageValue('sound') == null) {
									setSessionStorageValue('sound', 'on');
								}

								if (getSessionStorageValue('sound') == 'on') {
									$('div#ebotSound').html('<font color="green"><b>Sound On <a href="#" onclick="ebotSound(\'off\');">(Turn Off)</a></b></font>');
								}
								else if (getSessionStorageValue('sound') == 'off') {
									$('div#ebotSound').html('<font color="red"><b>Sound Off <a href="#" onclick="ebotSound(\'on\');">(Turn On)</a></b></font>');
								}
							});
						</script>
						<?= Nav::widget([
							'options' => ['class' => 'nav nav-pills nav-stacked'],
							'items'   => [
								['label' => 'Home', 'url' => ['/main/index']],
								('
									<li>
										<div class="input-group" style="margin: 5px;">
											<input class="form-control" id="match_id_go" size="16" type="text" placeholder="' . Yii::t('app', "Match id") . '">
											<span class="input-group-btn">
												<button class="btn btn-default" type="button" onclick="goToMatch();">' . Yii::t('app', "Go") . '</button>
											</span>
										</div>
									</li>
								'),
								['label' => 'Matches', 'url' => ['/matches/index']],
								['label' => 'Seasons', 'url' => ['/seasons/index']],
								['label' => 'Statistics', 'url' => ['/stats']],
								['label' => 'Global statistics', 'url' => ['/stats/global']],
								['label' => 'Statistics by map', 'url' => ['/stats/map']],
								['label' => 'Statistics by weapon', 'url' => ['/stats/weapon']],
								['label' => 'Ingame Help', 'url' => ['/main/ingame']],
							],
						]);
						?>
					</div>
				</div>
				<? if(!Yii::$app->user->isGuest and Yii::$app->user->identity->is_admin): ?>
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
							type: "GET",
							url: "<?= Yii::$app->urlManager->createAbsoluteUrl("/matches/controls/?id="); ?>" + match_id,
						}).done(function (msg) {
							var data = $.parseJSON(msg);
							var output = "";
							for (var i = 0; i < data.length; i++) {
								output += data[i];
							}
							$('#matchs-actions-' + match_id).children('td.matchs-actions-container').empty().append(output);
						});
					}
					$(document).ready(function () {
						PNotify.desktop.permission();
						initSocketIo(function (socket) {
							socket.emit("identify", {type: "matchs"});
							socket.on("matchsHandler", function (data) {
								var data = jQuery.parseJSON(data);
								if (data['content'] == 'stop')
									location.reload();
								else if (data['message'] == 'button') {
									getButtons(data['id']);
									$('#loading_' + data['id']).hide();
								}
								else if (data['message'] == 'streamerReady') {
									$('.streamer_' + data['id']).addClass('disabled');
									$('#loading_' + data['id']).hide();
									new PNotify({
										title: 'Streamers are ready now',
										type: 'info',
										text: $("#team_a-" + data['id']).text() + " vs " + $("#team_b-" + data['id']).text(),
										desktop: {
											desktop: true
										}
									});
								}
								else if (data['message'] == 'status') {
									if (data['content'] == 'Finished') {
										if (lastMatchEnd != data['id']) {
											new PNotify({
												title: 'Match finished',
												type: 'info',
												text: $("#team_a-" + data['id']).text() + " vs " + $("#team_b-" + data['id']).text(),
												desktop: {
													desktop: true
												}
											});
										}
										lastMatchEnd = data['id'];
										location.reload();
									}
									else if (data['content'] == 'is_paused') {
										new PNotify({
											title: 'Match paused!',
											type: 'info',
											text: $("#team_a-" + data['id']).text() + " vs " + $("#team_b-" + data['id']).text(),
											desktop: {
												desktop: true
											}
										});
										$("#flag-" + data['id']).attr('class', "label label-warning");
										if (getSessionStorageValue('sound') == "on")
											$("#soundHandle").trigger('play');
									}
									else if (data['content'] == 'is_unpaused') {
										new PNotify({
											title: 'Match unpaused!',
											type: 'info',
											text: $("#team_a-" + data['id']).text() + " vs " + $("#team_b-" + data['id']).text(),
											desktop: {
												desktop: true
											}
										});
										$("#flag-" + data['id']).attr('class', "label label-success");
										if (getSessionStorageValue('sound') == "on")
											$("#soundHandle").trigger('play');
									}
									else if (data['content'] != 'Starting') {
										if ($("#flag-" + data['id']).attr('class') == "label label-danger") {
											location.reload();
										}
										else {
											$("#flag-" + data['id']).attr('class', "label label-success");
											$('#loading_' + data['id']).hide();
										}
										$("div.status-" + data['id']).html(data['content']);
									}
								}
								else if (data['message'] == 'score') {
									if (data['scoreA'] < 10)
										data['scoreA'] = "0" + data['scoreA'];
									if (data['scoreB'] < 10)
										data['scoreB'] = "0" + data['scoreB'];

									if (enableNotifScore) {
										new PNotify({
											title: 'Score Update',
											type: 'info',
											text: $("#team_a-" + data['id']).text() + " (" + data['scoreA'] + ") vs (" + data['scoreB'] + ") " + $("#team_b-" + data['id']).text(),
											desktop: {
												desktop: true
											}
										});
									}

									if (data['scoreA'] == data['scoreB'])
										$("#score-" + data['id']).html("<span>" + data['scoreA'] + "</span> — <span>" + data['scoreB'] + "</span>");
									else if (data['scoreA'] > data['scoreB'])
										$("#score-" + data['id']).html("<span class=\"text-success\">" + data['scoreA'] + "</span> — <span class=\"text-danger\">" + data['scoreB'] + "</span>");
									else if (data['scoreA'] < data['scoreB'])
										$("#score-" + data['id']).html("<span class=\"text-danger\">" + data['scoreA'] + "</span> — <span class=\"text-success\">" + data['scoreB'] + "</span>");
								}
								else if (data['message'] == 'teams') {
									if (data['teamA'] == 'ct') {
										$("#team_a-" + data['id']).html("<span class='text-primary'>" + $("#team_a-" + data['id']).text() + "</span>")
										$("#team_b-" + data['id']).html("<span class='text-warning'>" + $("#team_b-" + data['id']).text() + "</span>")
									} else {
										$("#team_a-" + data['id']).html("<span class='text-warning'>" + $("#team_a-" + data['id']).text() + "</span>")
										$("#team_b-" + data['id']).html("<span class='text-primary'>" + $("#team_b-" + data['id']).text() + "</span>")
									}
								}
								else if (data['message'] == 'currentMap') {
									$("#map-" + data['id']).html(data['mapname']);
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

						var currentMatchAdmin = 0;
						$(function () {
							$(".bo3").popover();

							$.ajax({
								url: "/images/soundHandle/notify.mp3"
							}).done(function (data) {
								$("#soundHandle").attr('src', '/images/soundHandle/notify.mp3');
							});

							if (getSessionStorageValue("current.selected") != 0) {
								var value = getSessionStorageValue("current.selected");
								if ($("[data-id=" + value + "]:first").length == 1) {
									$("[data-id=" + value + "]:first").click();
								}
								else {
									setSessionStorageValue("current.selected", 0);
								}
							}
						});
					</script>

					<div class="panel panel-primary">
						<div class="panel-heading">Websocket status</div>
						<div class="panel-body">
							<div id="websocketAlive"><?= Yii::t('app', "Loading"); ?></div>
							<div id="ebotAlive"><?= Yii::t('app', "Loading"); ?></div>
							<div id="ebotSound"><?= Yii::t('app', "Loading"); ?></div>
						</div>
					</div>
				<? endif ?>
			</div>
			<div class="col-md-10">
				<?= Breadcrumbs::widget([
					'links' => isset($this->params['breadcrumbs'])? $this->params['breadcrumbs'] : [],
				]) ?>

				<?php if (Yii::$app->session->hasFlash('success')): ?>
					<div class="alert alert-success">
						<?= Yii::$app->session->getFlash('success') ?>
					</div>
				<?php endif; ?>
				<?php if (Yii::$app->session->hasFlash('error')): ?>
					<div class="alert alert-danger">
						<?= Yii::$app->session->getFlash('error') ?>
					</div>
				<?php endif; ?>
				<?php if (Yii::$app->session->hasFlash('warning')): ?>
					<div class="alert alert-warning">
						<?= Yii::$app->session->getFlash('warning') ?>
					</div>
				<?php endif; ?>
				<?php if (Yii::$app->session->hasFlash('info')): ?>
					<div class="alert alert-info">
						<?= Yii::$app->session->getFlash('info') ?>
					</div>
				<?php endif; ?>
				<?= $content ?>
			</div>
		</div>
	</div>
</div>

<footer class="footer">
	<div class="container-fluid">
		<p class="pull-left"><?= Yii::$app->name ?> WEB panel <?= Yii::$app->version ?> &copy; <a
					href="http://github.com/CodersGit">PolarWolf</a> 2016-<?= date('Y') ?></p>
		<p class="pull-right"><?= Yii::powered() ?> and <a href="http://getbootstrap.com" target="_blank">Bootstrap</a>
		</p>
	</div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
