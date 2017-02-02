<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "matches".
 *
 * @property string           $id
 * @property string           $ip
 * @property string           $server_id
 * @property string           $season_id
 * @property string           $team_a
 * @property string           $team_a_flag
 * @property string           $team_a_name
 * @property string           $team_b
 * @property string           $team_b_flag
 * @property string           $team_b_name
 * @property integer          $status
 * @property integer          $is_paused
 * @property string           $score_a
 * @property string           $score_b
 * @property integer          $max_round
 * @property string           $rules
 * @property string           $overtime_startmoney
 * @property integer          $overtime_max_round
 * @property integer          $config_full_score
 * @property integer          $config_ot
 * @property integer          $config_streamer
 * @property integer          $config_knife_round
 * @property integer          $config_switch_auto
 * @property integer          $config_auto_change_password
 * @property string           $config_password
 * @property integer          $config_heatmap
 * @property string           $config_authkey
 * @property integer          $enable
 * @property string           $map_selection_mode
 * @property integer          $ingame_enable
 * @property string           $current_map
 * @property integer          $force_zoom_match
 * @property string           $identifier_id
 * @property string           $startdate
 * @property integer          $auto_start
 * @property integer          $auto_start_time
 * @property string           $created_at
 * @property string           $updated_at
 *
 * @property Maps[]           $maps
 * @property Maps             $currentMap
 * @property Seasons          $season
 * @property Servers          $server
 * @property Teams            $teamA
 * @property Teams            $teamB
 * @property PlayerKill[]     $playerKills
 * @property Players[]        $players
 * @property PlayersHeatmap[] $playersHeatmaps
 * @property Round[]          $rounds
 * @property RoundSummary[]   $roundSummaries
 */
class Matches extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return 'matchs';
	}

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
			[
				[
					'server_id', 'season_id', 'team_a', 'team_b', 'status', 'is_paused', 'score_a', 'score_b',
					'max_round', 'overtime_startmoney', 'overtime_max_round', 'config_full_score', 'config_ot',
					'config_streamer', 'config_knife_round', 'config_switch_auto', 'config_auto_change_password',
					'config_heatmap', 'enable', 'ingame_enable', 'current_map', 'force_zoom_match', 'auto_start',
					'auto_start_time',
				], 'integer',
			],
			[['max_round', 'rules', 'created_at', 'updated_at'], 'required'],
			[['startdate', 'created_at', 'updated_at'], 'safe'],
			[['ip', 'config_password'], 'string', 'max' => 50],
			[['team_a_flag', 'team_b_flag'], 'string', 'max' => 2],
			[['team_a_name', 'team_b_name'], 'string', 'max' => 25],
			[['rules', 'config_authkey'], 'string', 'max' => 200],
			[['map_selection_mode'], 'string', 'max' => 255],
			[['identifier_id'], 'string', 'max' => 100],
			[
				['current_map'], 'exist', 'skipOnError'     => true, 'targetClass' => Maps::className(),
				                          'targetAttribute' => ['current_map' => 'id'],
			],
			[
				['season_id'], 'exist', 'skipOnError'     => true, 'targetClass' => Seasons::className(),
				                        'targetAttribute' => ['season_id' => 'id'],
			],
			[
				['server_id'], 'exist', 'skipOnError'     => true, 'targetClass' => Servers::className(),
				                        'targetAttribute' => ['server_id' => 'id'],
			],
			[
				['team_a'], 'exist', 'skipOnError'     => true, 'targetClass' => Teams::className(),
				                     'targetAttribute' => ['team_a' => 'id'],
			],
			[
				['team_b'], 'exist', 'skipOnError'     => true, 'targetClass' => Teams::className(),
				                     'targetAttribute' => ['team_b' => 'id'],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'id'                          => 'ID',
			'ip'                          => 'IP',
			'server_id'                   => 'Server ID',
			'season_id'                   => 'Season ID',
			'team_a'                      => 'Team A',
			'team_a_flag'                 => 'Team A flag',
			'team_a_name'                 => 'Team A',
			'team_b'                      => 'Team B',
			'team_b_flag'                 => 'Team B flag',
			'team_b_name'                 => 'Team B',
			'status'                      => 'Status',
			'is_paused'                   => 'Is paused',
			'score_a'                     => 'Team A score',
			'score_b'                     => 'Team B score',
			'max_round'                   => 'Max round',
			'rules'                       => 'Rules',
			'overtime_startmoney'         => 'Overtime Startmoney',
			'overtime_max_round'          => 'Overtime Max Round',
			'config_full_score'           => 'Play all rounds',
			'config_ot'                   => 'Overtimes',
			'config_streamer'             => 'Streamer Ready',
			'config_knife_round'          => 'Knife Round',
			'config_switch_auto'          => 'Switch Auto',
			'config_auto_change_password' => 'Auto Change Password',
			'config_password'             => 'Password',
			'config_heatmap'              => 'Heatmap',
			'config_authkey'              => 'Authkey',
			'enable'                      => 'Enable',
			'map_selection_mode'          => 'Map Selection Mode',
			'ingame_enable'               => 'Ingame Enable',
			'current_map'                 => 'Current Map',
			'force_zoom_match'            => 'Force Zoom Match',
			'identifier_id'               => 'Identifier ID',
			'startdate'                   => 'Startdate',
			'auto_start'                  => 'Auto Start',
			'auto_start_time'             => 'Auto Start Time',
			'created_at'                  => 'Created At',
			'updated_at'                  => 'Updated At',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMaps () {
		return $this->hasMany(Maps::className(), ['match_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCurrentMap () {
		return $this->hasOne(Maps::className(), ['id' => 'current_map']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSeason () {
		return $this->hasOne(Seasons::className(), ['id' => 'season_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getServer () {
		return $this->hasOne(Servers::className(), ['id' => 'server_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTeamA () {
		return $this->hasOne(Teams::className(), ['id' => 'team_a']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTeamB () {
		return $this->hasOne(Teams::className(), ['id' => 'team_b']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPlayerKills () {
		return $this->hasMany(PlayerKill::className(), ['match_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPlayers () {
		return $this->hasMany(Players::className(), ['match_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPlayersHeatmaps () {
		return $this->hasMany(PlayersHeatmap::className(), ['match_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRounds () {
		return $this->hasMany(Round::className(), ['match_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRoundSummaries () {
		return $this->hasMany(RoundSummary::className(), ['match_id' => 'id']);
	}
}
