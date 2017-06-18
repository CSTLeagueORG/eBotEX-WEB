<?php

	/* @var $this \yii\web\View */
	/* @var $content string */

	use yii\helpers\Html;
	use yii\bootstrap\Nav;
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
		$.getScript("://" + socketIoAddress + "/socket.io/socket.io.js", function () {
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
									document.location.href = "<?= Yii::$app->urlManager->createAbsoluteUrl("/matches/view/?id="); ?>" + id;
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
