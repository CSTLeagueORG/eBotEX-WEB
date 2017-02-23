<?php

use app\models\Matches\Round;
use \app\models\Stats\PlayerKill;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $match app\models\Matches\Matches */

$last = null;
$data = array();
$i = -1;
$count = 0;
$rounds = $match->roundSummaries;

$round_current = 0;
$round_a = 0;
$round_b = 0;

$stats = array();
$stats["team_a"] = array(
	"bomb_planted_win" => 0,
	"bomb_planted_loose" => 0,
	"bomb_explosed" => 0,
	"bomb_defused" => 0,
	"kill" => 0,
	"time" => 0
);

$stats["team_b"] = array(
	"bomb_planted_win" => 0,
	"bomb_planted_loose" => 0,
	"bomb_explosed" => 0,
	"bomb_defused" => 0,
	"kill" => 0,
	"time" => 0
);

foreach ($rounds as $round) {
	if ($round->team_win == "a")
		$win = "ct";
	else
		$win = "t";

	if ($round->team_win == "a") {
		if (($round->win_type == "normal") && $round->t_win && $round->bomb_planted) {
			$stats["team_a"]["bomb_planted_win"]++;
		} elseif (($round->win_type == "normal") && !$round->bomb_planted) {
			$stats["team_a"]["kill"]++;
		} elseif (($round->win_type == "bombdefused")) {
			$stats["team_a"]["bomb_defused"]++;
			$stats["team_b"]["bomb_planted_loose"]++;
		} elseif ($round->win_type == "saved") {
			$stats["team_a"]["time"]++;
		} elseif (($round->win_type == "bombeexploded")) {
			$stats["team_a"]["bomb_explosed"]++;
		}
	}

	if ($round->team_win == "b") {
		if (($round->win_type == "normal") && $round->t_win && $round->bomb_planted) {
			$stats["team_b"]["bomb_planted_win"]++;
		} elseif (($round->win_type == "normal") && !$round->bomb_planted) {
			$stats["team_b"]["kill"]++;
		} elseif (($round->win_type == "bombdefused")) {
			$stats["team_b"]["bomb_defused"]++;
			$stats["team_a"]["bomb_planted_loose"]++;
		} elseif ($round->win_type == "saved") {
			$stats["team_b"]["time"]++;
		} elseif (($round->win_type == "bombeexploded")) {
			$stats["team_b"]["bomb_explosed"]++;
		}
	}



	if (($round->team_win == "a") && ($last != $win)) {
		if ($round_current > $round_b) {
			$round_b = $round_current;
		}
	} elseif (($round->team_win == "b") && ($last != $win)) {
		if ($round_current > $round_a) {
			$round_a = $round_current;
		}
	}

	if ($last == $win) {
		$round_current++;
		$data[$i]["type"] = $win;
		@$data[$i]["value"]++;
	} else {
		$data[++$i] = array("type" => $win, "value" => 1);
		$round_current = 1;
	}

	$count++;
	if ($count == $match->max_round) {
		$data[++$i] = array("type" => "seperator", "value" => 1);
		++$i;
	}
	$last = $win;
}

if ($last == "ct") {
	if ($round_current > $round_a) {
		$round_a = $round_current;
	}
} else {
	if ($round_current > $round_b) {
		$round_b = $round_current;
	}
}


$size = 450 / ($match->max_round * 2 + 1);
?>

	<table border="0" cellpadding="5" cellspacing="5" width="100%">
		<tr>
			<td width="50%" valign="top">
				<h5><b><i class="fa fa-hand-o-right"></i> <?= Yii::t('app', "Match Statistics"); ?></b></h5>

				<table class="table">
					<tr>
						<th width="200"><?= Yii::t('app', "Round Series"); ?></th>
						<td>
							<div class="progress" style="width: 450px;">
								<?php foreach ($data as $d): ?>
									<?php if ($d["type"] == "seperator"): ?>
										<div class="progress-bar progress-bar-warning needTips" title="<?= Yii::t('app', "Halftime"); ?>" style="width: <?php echo $size * 1; ?>px;" >
										</div>
									<?php else: ?>
										<div <?php if ($d["value"] > 1) : $class = "needTips"; ?>title="<?php echo $d["value"]; ?> <?= Yii::t('app', "rounds"); ?>"<?php endif; ?> class="progress-bar <?php echo ($d["type"] == "ct") ? "" : "progress-bar-danger"; ?> <?php echo $class; ?>" style="width: <?php echo $size * $d["value"]; ?>px;"></div>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
							<div style="clear: both"></div>
						</td>
					</tr>
					<tr>
						<th width="200"><?= Yii::t('app', "Caption"); ?></th>
						<td>
							<div class="progress" style="width: 25px; height: 25px; float: left; margin-right: 10px;">
								<div class="progress-bar" style="width: 100%"></div>
							</div>
							<b><?php echo $match->teamA ? $match->teamA->name : $match->team_a_name; ?></b>
							<div style="clear:both; margin-top: 10px;"></div>

							<div class="progress" style="width: 25px; height: 25px; float: left; margin-right: 10px;">
								<div class="progress-bar progress-bar-danger" style="width: 100%"></div>
							</div>
							<b><?php echo $match->teamB ? $match->teamB->name : $match->team_b_name; ?></b>
						</td>
					</tr>
				</table>
			</td>
			<td width="50%" valign="top">
				<table class="table table-striped table-condensed">
					<tr>
						<th width="200"></th>
						<th><?php echo $match->teamA ? $match->teamA->name : $match->team_a_name; ?> <?php if ($match->score_a > $match->score_b): ?><i class="fa fa-star"></i><?php endif; ?></th>
						<th><?php echo $match->teamB ? $match->teamB->name : $match->team_b_name; ?> <?php if ($match->score_b > $match->score_a): ?><i class="fa fa-star"></i><?php endif; ?></th>
					</tr>
					<tr>
						<th width="200"><?= Yii::t('app', "Victory: Bomb Exploded"); ?></th>
						<td><?php echo $stats["team_a"]["bomb_planted_win"]; ?> <?php if ($stats["team_a"]["bomb_planted_win"] > $stats["team_b"]["bomb_planted_win"]): ?><i class="fa fa-star"></i><?php endif; ?></td>
						<td><?php echo $stats["team_b"]["bomb_planted_win"]; ?> <?php if ($stats["team_b"]["bomb_planted_win"] > $stats["team_a"]["bomb_planted_win"]): ?><i class="fa fa-star"></i><?php endif; ?></td>
					</tr>
					<tr>
						<th width="200"><?= Yii::t('app', "Defeat: Bomb Defused"); ?></th>
						<td><?php echo $stats["team_a"]["bomb_planted_loose"]; ?> <?php if ($stats["team_a"]["bomb_planted_loose"] > $stats["team_b"]["bomb_planted_loose"]): ?><i class="fa fa-star"></i><?php endif; ?></td>
						<td><?php echo $stats["team_b"]["bomb_planted_loose"]; ?> <?php if ($stats["team_b"]["bomb_planted_loose"] > $stats["team_a"]["bomb_planted_loose"]): ?><i class="fa fa-star"></i><?php endif; ?></td>
					</tr>
					<tr>
						<th width="200"><?= Yii::t('app', "Victory: Bomb Defused"); ?></th>
						<td><?php echo $stats["team_a"]["bomb_defused"]; ?> <?php if ($stats["team_a"]["bomb_defused"] > $stats["team_b"]["bomb_defused"]): ?><i class="fa fa-star"></i><?php endif; ?></td>
						<td><?php echo $stats["team_b"]["bomb_defused"]; ?> <?php if ($stats["team_b"]["bomb_defused"] > $stats["team_a"]["bomb_defused"]): ?><i class="fa fa-star"></i><?php endif; ?></td>
					</tr>
					<tr>
						<th width="200"><?= Yii::t('app', "Victory"); ?></th>
						<td><?php echo $stats["team_a"]["bomb_explosed"]; ?> <?php if ($stats["team_a"]["bomb_explosed"] > $stats["team_b"]["bomb_explosed"]): ?><i class="fa fa-star"></i><?php endif; ?></td>
						<td><?php echo $stats["team_b"]["bomb_explosed"]; ?> <?php if ($stats["team_b"]["bomb_explosed"] > $stats["team_a"]["bomb_explosed"]): ?><i class="fa fa-star"></i><?php endif; ?></td>
					</tr>
					<tr>
						<th width="200"><?= Yii::t('app', "Total Elimination"); ?></th>
						<td><?php echo $stats["team_a"]["kill"]; ?> <?php if ($stats["team_a"]["kill"] > $stats["team_b"]["kill"]): ?><i class="fa fa-star"></i><?php endif; ?></td>
						<td><?php echo $stats["team_b"]["kill"]; ?> <?php if ($stats["team_b"]["kill"] > $stats["team_a"]["kill"]): ?><i class="fa fa-star"></i><?php endif; ?></td>
					</tr>
					<tr>
						<th width="200"><?= Yii::t('app', "Victory: Out of Time"); ?></th>
						<td><?php echo $stats["team_a"]["time"]; ?> <?php if ($stats["team_a"]["time"] > $stats["team_b"]["time"]): ?><i class="fa fa-star"></i><?php endif; ?></td>
						<td><?php echo $stats["team_b"]["time"]; ?> <?php if ($stats["team_b"]["time"] > $stats["team_a"]["time"]): ?><i class="fa fa-star"></i><?php endif; ?></td>
					</tr>
					<tr>
						<th width="200"><?= Yii::t('app', "Longest Round Streak"); ?></th>
						<td>
							<?php echo $round_a; ?> <?php if ($round_a > $round_b): ?><i class="fa fa-star"></i><?php endif; ?>
						</td>
						<td>
							<?php echo $round_b; ?> <?php if ($round_b > $round_a): ?><i class="fa fa-star"></i><?php endif; ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<hr/>

<?php if (count($rounds) > 0): ?>
	<h5><b><i class="fa fa-leaf"></i> <?= Yii::t('app', "Round Details"); ?></b></h5>
	<hr/>

	<script>
		var round_max_time = 120;
		var timer_event_default = 0;
		var default_decal = 10;


		$(function() {
			$('.carousel').carousel();
			$("#myCarousel").on("slid.bs.carousel", function() {
				var nav = $("#paginatorRound");
				var index = $(this).find('.item.active').index();
				var item = nav.find('li').get(index);

				nav.find('li.active').removeClass('active');
				$(item).addClass('active');

				index++;

				generateTimeLine(index);
			});

			$("#paginatorRound").find("li:first").addClass("active");
			//generateTimeLine(1);
		});

		function generateTimeLine(index) {
			if (event[index]) {
				var paper = Raphael("canvas-"+index, $("#canvas-"+index).width(), 30);
				var rect = paper.rect(10,10,$("#canvas-"+index).width()-20,5,3);
				rect.attr("fill", "#000");
				rect.attr("stroke", "#fff");

				timer_event_default = ($("#canvas-"+index).width()-20)/round_max_time;
				$(event[index]).each(function() {
					var e = this;
					var default_pos = default_decal+(this.time)*timer_event_default;
					var title = e.text;
					var circle = paper.circle(default_pos, 12, 8);
					circle.attr( {"fill" : this.color, "stroke" : "#fff", "stroke-width" : 2 });
					$(circle.node).attr("id", "circle-"+index+"-"+e.time);
					$(circle.node).tooltip({position: 'bottom', title: title, container: "#canvas-"+index});
				});

				event[index] = false;
			}

		}

		function goToRound(id) {
			$("#myCarousel").carousel("pause");
			$("#myCarousel").carousel(id-1);
		}

		var event = new Array();
	</script>
	<div>
		<div id="myCarousel" class="carousel slide">
			<div class="carousel-inner">
				<?php $first = true; ?>
				<?php foreach ($rounds as $round): ?>
					<div class="item <?php if ($first) echo "active"; $first = false; ?>" style="margin-left: 50px; margin-right: 50px;">
						<div style="width: 90%; height: 340px; margin:auto;">
							<div id="canvas-<?php echo $round->round_id; ?>" style="width: 100%; position: relative;">
							</div>
							<?php
							$eventArray = array();
							$events = Round::find()->where(["map_id" => $match->currentMap->id])->andWhere(["round_id" => $round->round_id])->orderBy('event_time')->all();
							foreach ($events as $event) {
								$color = "#000";
								$text = $event->event_name;
								if ($event->event_name == "round_start") {
									$text = "Round start";
								} elseif ($event->event_name == "kill") {
									$color = "#F00";
									$kill = $event->kill;
									if (!$kill)
										continue;
									$text = $kill->killer_name . " killed " . $kill->killed_name . " with " . $kill->weapon;
								} elseif ($event->event_name == "round_end") {
									$text = "End of the round";
								} elseif ($event->event_name == "bomb_planting") {
									$color = "#A00";
									$text = "Bomb has been planted";
								} elseif ($event->event_name == "bomb_defusing") {
									$color = "#A00";
									$text = "Started bomb defusing";
								} elseif ($event->event_name == "1vx") {
									$data = unserialize($event->event_text);
									$color = "#00F";
									$text = "Situation 1v" . $data["situation"];
								} elseif ($event->event_name == "bomb_defused") {
									$text = "Bomb has been defused";
								} elseif ($event->event_name == "1vx_ok") {
									$text = "Clutch 1vx succeed";
								} elseif ($event->event_name == "1v1") {
									$text = "Situation 1v1";
								} elseif ($event->event_name == "bomb_exploded") {
									$text = "Bomb exploded";
								}

								if (count($eventArray) > 0) {
									$e = array_pop($eventArray);
									if ($e["time"] == $event->event_name) {
										$e["text"].= " $text";
										$e["color"] = $color;
										$eventArray[] = $e;
										continue;
									}
									$eventArray[] = $e;
								}
								$eventArray[] = array("type" => $event->event_name, "time" => $event->event_time, "color" => $color, "text" => "" . $event->event_time . "s : " . $text);
							}
							?>

							<script>
								event[<?php echo $round->round_id; ?>] = <?php echo json_encode($eventArray) ?>;
							</script>
							<table border="0" cellpadding="5" cellspacing="5" width="100%">
								<tr>
									<td valign="top" width="50%">
										<h5><?= Yii::t('app', "Round #").$round->round_id; ?></h5>
										<table class="table">
											<tr>
												<th width="200"><?= Yii::t('app', "Winner"); ?></th>
												<td>
													<?php
													if ($round->team_win == "a")
														echo $match->teamA ? $match->teamA->name : $match->team_a_name;
													else
														echo $match->teamB ? $match->teamB->name : $match->team_b_name;
													?>
												</td>
											</tr>
											<tr>
												<th width="200"><?= Yii::t('app', "Victory by"); ?></th>
												<td>
													<?php
													switch ($round->win_type) {
														case "bombdefused":
															echo Yii::t('app', "Bomb defused");
															break;
														case "bombeexploded":
															echo Yii::t('app', "Bomb exploded");
															break;
														case "normal":
															echo Yii::t('app', "Total Elimination");
															break;
														case "saved":
															echo Yii::t('app', "Out of Time");
															break;
													}
													?>
												</td>
											</tr>
											<tr>
												<th width="200"><?= Yii::t('app', "Bomb planted"); ?></th>
												<td><span class="label label-<?= ($round->bomb_planted)? "success":"danger" ?>"><i class="fa fa-flag"></i></span></td>
											</tr>
											<tr>
												<th width="200"><?= Yii::t('app', "Bombe defused"); ?></th>
												<td><span class="label label-<?= ($round->bomb_defused)? "success":"danger" ?>"><i class="fa fa-flag"></i></span></td>
											</tr>
											<tr>
												<th width="200"><?= Yii::t('app', "Bombe exploded"); ?></th>
												<td><span class="label label-<?= ($round->bomb_exploded)? "success":"danger" ?>"><i class="fa fa-flag"></i></span></td>
											</tr>
											<?php if ($round->best_action_type != ""): ?>
												<tr>
													<th width="200"><?= Yii::t('app', "Action of the Round"); ?></th>
													<td>
														<?php if (preg_match("!^1v(\d+)$!", $round->best_action_type, $m)): ?>
															<?php $d = unserialize($round->best_action_param); ?>
															<?php echo $d["playerName"]; ?> <?= Yii::t('app', " had clutch "); ?> <?php echo $round->best_action_type; ?>
														<?php elseif (preg_match("!^(\d+)kill$!", $round->best_action_type, $m)): ?>
															<? $_COOKIE['tset'] = $round->id ?>
															<?php $d = unserialize($round->best_action_param); ?>
															<?php echo $d["playerName"]; ?> <?= Yii::t('app', "did"); ?> <?php echo $m[1]; ?> <?= Yii::t('app', "kills"); ?>
														<?php endif; ?>
													</td>
												</tr>
											<?php endif; ?>
										</table>
									</td>
									<td valign="top">
										<h5><?= Yii::t('app', "Round Details"); ?></h5>
										<table class="table table-striped table-condensed">
											<?php foreach (PlayerKill::find()->where(["map_id" => $match->currentMap->id])->andWhere(['round_id' => $round->round_id])->all() as $kill): ?>
												<tr>
													<td width="250">
														<?php
														if ($kill->killer_team == "CT")
															$color = "blue";
														elseif ($kill->killer_team == "TERRORIST")
															$color = "red";
														else
															$color = "black";
														?>
														<span style="color: <?php echo $color; ?>"><?php echo $kill->killer_name; ?></span>
													</td>

													<td width="100">
														<?= Html::img(Yii::$app->urlManager->createAbsoluteUrl("images/kills/csgo/" . $kill->weapon . ".png"), array("class" => "needTips", "title" => $kill->weapon)); ?>
														<?php if ($kill->headshot): ?>
															<?= Html::img(Yii::$app->urlManager->createAbsoluteUrl("images/kills/csgo/headshot.png")); ?>
														<?php endif; ?>
													</td>

													<td>
														<?php
														if ($kill->killed_team == "CT")
															$color = "blue";
														elseif ($kill->killed_team == "TERRORIST")
															$color = "red";
														else
															$color = "black";
														?>
														<span style="color: <?php echo $color; ?>"><?php echo $kill->killed_name; ?></span>
													</td>
												</tr>

											<?php endforeach; ?>
										</table>
									</td>
								</tr>
							</table>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>
		<div style="clear: both"></div>

		<script>
			var paused = false;
			function pauseResume() {
				if (paused) {
					$("#myCarousel").carousel("cycle");
					$("#buttonPauseResume").removeClass().addClass("fa fa-pause");
				} else {
					$("#buttonPauseResume").removeClass().addClass("fa fa-play");
					$("#myCarousel").carousel("pause");
				}
				paused = !paused;
				return false;
			}
			$(document).on('mouseleave','.carousel', function(){
				if (paused) {
					$(this).carousel('pause');
				}
			});
		</script>

		<table width="100%">
			<tr>
				<td align="center">
					<ul class="pagination" id="paginatorRound">
						<?php foreach ($rounds as $round): ?>
							<li><a href="#" onclick="goToRound(<?php echo $round->round_id; ?>); return false;"><?php echo $round->round_id; ?></a></li>
						<?php endforeach; ?>
						<li><a href="#" onclick="return pauseResume();">&nbsp;<i class="fa fa-pause" id="buttonPauseResume"></i></a></li>
					</ul>
				</td>
			</tr>
		</table>
	</div>
<?php endif; ?>