<span class="<?= (($model->currentMap->current_side == 'ct')? 'text-primary' : 'text-warning') ?>">
	<?= (($model->teamA)? $model->teamA->name : $model->team_a_name) ?>
</span>
<?= ((strlen($model->team_a_flag) == 2)? '<i class="teamflag teamflag-' . strtolower($model->team_a_flag) . '"></i> ' : '') ?>
<span class="<?= (($model->score_a <= $model->score_b)? ($model->score_a == $model->score_b)? '' : 'text-danger' : 'text-success') ?>">
	<?= $model->score_a ?>
</span>
—
<span class="<?= (($model->score_b <= $model->score_a)? ($model->score_a == $model->score_b)? '' : 'text-danger' : 'text-success') ?>">
	<?= $model->score_b ?>
</span>
<?= ((strlen($model->team_b_flag) == 2)? '<i class="teamflag teamflag-' . strtolower($model->team_b_flag)  . '"></i>' : '') ?>
<span class="<?= (($model->currentMap->current_side == 'ct')? 'text-warning' : 'text-primary') ?>">
	<?= (($model->teamB)? $model->teamB->name : $model->team_b_name) ?>
</span>