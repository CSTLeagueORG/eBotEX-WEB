<?php

	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use dosamigos\selectize\SelectizeDropDownList;

	/* @var $this yii\web\View */
	/* @var $model app\models\Matches\MatchesForm */
	/* @var $form ActiveForm */
?>

<script>
	$(document).ready(function () {

		// Remember the full configuration
		team_content = $('#matchesform-team_a').html();

		$('#matchesform-season').change(
			function () {
				if ($(this).val() != "") {
					$.ajax({
						url: "<?= Yii::$app->urlManager->createAbsoluteUrl("teams/getbyseasons") ?>",
						data: {season_id: $(this).val()},
						datatype: "json",
						type: "POST",
						success: function (data) {
							setTeamData(data);
						}
					});
				}
				else {
					$('#matchesform-team_a').empty();
					$('#matchesform-team_b').empty();
					$('#matchesform-team_a').append(team_content);
					$('#matchesform-team_b').append(team_content);
				}
			}
		);

		if ($('#matchesform-season').val() != "") {
			$.ajax({
				url: "<?= Yii::$app->urlManager->createAbsoluteUrl("teams/getbyseasons") ?>",
				data: {season_id: $('#matchesform-season').val()},
				datatype: "json",
				type: "POST",
				success: function (data) {
					setTeamData(data);
				}
			});
		}

		function setTeamData(data) {
			var data = jQuery.parseJSON(data);
			$('#matchesform-team_a').empty();
			$('#matchesform-team_b').empty();
			if (!$.isEmptyObject(data)) {
				var optionsAsString = '<option value="" selected="selected"></option>';
				for (var i = 0; i < data['id'].length; i++) {
					optionsAsString += "<option value='" + data['id'][i] + "'>" + data['name'][i] + " (" + data['flag'][i] + ") </option>";
				}
				$('#matchesform-team_a').append($(optionsAsString));
				$('#matchesform-team_b').append($(optionsAsString));
			}
		}

		$("#matchesform-team_a").change(
			function () {
				if ($(this).val() == '') {
					$(".team-a-fields").show();
				}
				else {
					$(".team-a-fields").hide();
				}
			}
		);

		$("#matchesform-team_b").change(
			function () {
				if ($(this).val() == '') {
					$(".team-b-fields").show();
				}
				else {
					$(".team-b-fields").hide();
				}
			}
		);

		$("#matchesform-map_selection_mode").change(
			function () {
				var settings = $('#matchesform-maps')[0].selectize.settings;
				settings.maxItems = $(this).val();
				settings.mode = 'multi';
				$('#matchesform-maps')[0].selectize.destroy();
				$('#matchesform-maps').selectize(settings);
			}
		);

		$("#matchesform-overtime").click(function () {
			if ($(this).is(':checked')) {
				$(".overtime-fields").show();
			}
			else {
				$(".overtime-fields").hide();
			}
		});

		$("#matchesform-tac_pause").click(function () {
			if ($(this).is(':checked')) {
				$(".tac-pause-fields").show();
			}
			else {
				$(".tac-pause-fields").hide();
			}
		});

		$("#matchesform-autostart").click(function () {
			if ($(this).is(':checked')) {
				$(".autostart-fields").show();
			}
			else {
				$(".autostart-fields").hide();
			}
		});
	});
</script>

<? if($model->errors): ?>
	<div class="alert alert-danger">
		<?= $model->errors ?>
	</div>
<? endif; ?>

<div class="form">

	<?php $form = ActiveForm::begin(); ?>

	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'season')->dropDownList($model->getSeasons(), ['prompt' => '']) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'team_a')->dropDownList($model->getTeams(), ['prompt' => '']) ?>
		</div>
		<div class="col-md-4 team-a-fields"<?= (!$model->team_a)? '' : ' style="display:none;"' ?>>
			<?= $form->field($model, 'team_a_name') ?>
		</div>
		<div class="col-md-4 team-a-fields"<?= (!$model->team_a)? '' : ' style="display:none;"' ?>>
			<?= $form->field($model, 'team_a_flag')->dropDownList(Yii::$app->params['countries'], ['prompt' => '']) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'team_b')->dropDownList($model->getTeams(), ['prompt' => '']) ?>
		</div>
		<div class="col-md-4 team-b-fields"<?= (!$model->team_b)? '' : ' style="display:none;"' ?>>
			<?= $form->field($model, 'team_b_name') ?>
		</div>
		<div class="col-md-4 team-b-fields"<?= (!$model->team_b)? '' : ' style="display:none;"' ?>>
			<?= $form->field($model, 'team_b_flag')->dropDownList(Yii::$app->params['countries'], ['prompt' => '']) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'rules') ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'password') ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'max_rounds')->dropDownList($model->getMaxRounds()) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'map_selection_mode')->dropDownList($model->getMapSelectionModes()) ?>
		</div>
		<div class="col-md-8">
			<?= $form->field($model, 'maps')->widget(SelectizeDropDownList::className(), ['items'         => $model->getMaps(),
			                                                                              'options'       => ['multiple' => true],
			                                                                              'clientOptions' => ['maxItems' => $model->map_selection_mode],
			]) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'server')->dropDownList($model->getServers()) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'first_side')->dropDownList($model->getSides()) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'play_all_rounds')->checkbox() ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'streamer_ready')->checkbox() ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'knife_round')->checkbox() ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'overtime')->checkbox() ?>
		</div>
		<div class="col-md-4 overtime-fields"<?= $model->overtime? '' : ' style="display:none;"' ?>>
			<?= $form->field($model, 'overtime_start_money') ?>
		</div>
		<div class="col-md-4 overtime-fields"<?= $model->overtime? '' : ' style="display:none;"' ?>>
			<?= $form->field($model, 'overtime_max_rounds')->dropDownList($model->getMaxRounds()) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'tac_pause')->checkbox() ?>
		</div>
		<div class="col-md-4 tac-pause-fields"<?= $model->tac_pause? '' : ' style="display:none;"' ?>>
			<?= $form->field($model, 'tac_pause_max') ?>
		</div>
		<div class="col-md-4 tac-pause-fields"<?= $model->tac_pause? '' : ' style="display:none;"' ?>>
			<?= $form->field($model, 'tac_pause_duration') ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'autostart')->checkbox() ?>
		</div>
		<div class="col-md-4 autostart-fields"<?= $model->autostart? '' : ' style="display:none;"' ?>>
			<?= $form->field($model, 'startdate')->input('date') ?>
		</div>
		<div class="col-md-4 autostart-fields"<?= $model->autostart? '' : ' style="display:none;"' ?>>
			<?= $form->field($model, 'start_before_startdate') ?>
		</div>
	</div>

	<div class="form-group">
		<?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
	</div>
	<?php ActiveForm::end(); ?>

</div><!-- _form -->
