<?php

namespace app\models\Matches;

use app\models\Stats\Players;

use Yii;

/**
 * This is the model class for table "round_summary".
 *
 * @property string  $id
 * @property string  $match_id
 * @property string  $map_id
 * @property integer $bomb_planted
 * @property integer $bomb_defused
 * @property integer $bomb_exploded
 * @property string  $win_type
 * @property string  $team_win
 * @property integer $ct_win
 * @property integer $t_win
 * @property integer $score_a
 * @property integer $score_b
 * @property string  $best_killer
 * @property string  $best_killer_nb
 * @property integer $best_killer_fk
 * @property string  $best_action_type
 * @property string  $best_action_param
 * @property string  $backup_file_name
 * @property string  $round_id
 * @property string  $created_at
 * @property string  $updated_at
 *
 * @property Players $bestKiller
 * @property Maps    $map
 * @property Matches $match
 */
class RoundSummary extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return 'round_summary';
	}

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
			[['match_id', 'map_id', 'created_at', 'updated_at'], 'required'],
			[
				[
					'match_id', 'map_id', 'bomb_planted', 'bomb_defused', 'bomb_exploded', 'ct_win', 't_win', 'score_a',
					'score_b', 'best_killer', 'best_killer_nb', 'best_killer_fk', 'round_id',
				], 'integer',
			],
			[['best_action_type', 'best_action_param'], 'string'],
			[['created_at', 'updated_at'], 'safe'],
			[['win_type', 'team_win', 'backup_file_name'], 'string', 'max' => 255],
			[
				['best_killer'], 'exist', 'skipOnError'     => true, 'targetClass' => Players::className(),
				                          'targetAttribute' => ['best_killer' => 'id'],
			],
			[
				['map_id'], 'exist', 'skipOnError'     => true, 'targetClass' => Maps::className(),
				                     'targetAttribute' => ['map_id' => 'id'],
			],
			[
				['match_id'], 'exist', 'skipOnError'     => true, 'targetClass' => Matches::className(),
				                       'targetAttribute' => ['match_id' => 'id'],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'id'                => Yii::t('app', 'ID'),
			'match_id'          => Yii::t('app', 'Match ID'),
			'map_id'            => Yii::t('app', 'Map ID'),
			'bomb_planted'      => Yii::t('app', 'Bomb Planted'),
			'bomb_defused'      => Yii::t('app', 'Bomb Defused'),
			'bomb_exploded'     => Yii::t('app', 'Bomb Exploded'),
			'win_type'          => Yii::t('app', 'Win Type'),
			'team_win'          => Yii::t('app', 'Team Win'),
			'ct_win'            => Yii::t('app', 'Ct Win'),
			't_win'             => Yii::t('app', 'T Win'),
			'score_a'           => Yii::t('app', 'Score A'),
			'score_b'           => Yii::t('app', 'Score B'),
			'best_killer'       => Yii::t('app', 'Best Killer'),
			'best_killer_nb'    => Yii::t('app', 'Best Killer Nb'),
			'best_killer_fk'    => Yii::t('app', 'Best Killer Fk'),
			'best_action_type'  => Yii::t('app', 'Best Action Type'),
			'best_action_param' => Yii::t('app', 'Best Action Param'),
			'backup_file_name'  => Yii::t('app', 'Backup File Name'),
			'round_id'          => Yii::t('app', 'Round ID'),
			'created_at'        => Yii::t('app', 'Created At'),
			'updated_at'        => Yii::t('app', 'Updated At'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBestKiller () {
		return $this->hasOne(Players::className(), ['id' => 'best_killer']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMap () {
		return $this->hasOne(Maps::className(), ['id' => 'map_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMatch () {
		return $this->hasOne(Matches::className(), ['id' => 'match_id']);
	}
}
