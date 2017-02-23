<?php

namespace app\models\Matches;
use app\models\Stats\Players;
use app\models\Stats\PlayerKill;
use app\models\Stats\PlayersHeatmap;

use Yii;

/**
 * This is the model class for table "maps".
 *
 * @property string           $id
 * @property string           $match_id
 * @property string           $map_name
 * @property string           $score_1
 * @property string           $score_2
 * @property string           $current_side
 * @property integer          $status
 * @property string           $maps_for
 * @property string           $nb_ot
 * @property string           $identifier_id
 * @property string           $tv_record_file
 * @property string           $created_at
 * @property string           $updated_at
 *
 * @property Matches          $match
 * @property MapsScore[]      $mapsScores
 * @property Matches[]        $matches
 * @property PlayerKill[]     $playerKills
 * @property Players[]        $players
 * @property PlayersHeatmap[] $playersHeatmaps
 * @property Round[]          $rounds
 * @property RoundSummary[]   $roundSummaries
 */
class Maps extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return 'maps';
	}

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
			[['match_id', 'created_at', 'updated_at'], 'required'],
			[['match_id', 'score_1', 'score_2', 'status', 'nb_ot', 'identifier_id'], 'integer'],
			[['created_at', 'updated_at'], 'safe'],
			[['map_name'], 'string', 'max' => 50],
			[['current_side', 'maps_for', 'tv_record_file'], 'string', 'max' => 255],
			[['match_id'], 'exist', 'skipOnError'     => true, 'targetClass' => Matches::className(),
			                        'targetAttribute' => ['match_id' => 'id'],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'id'             => 'ID',
			'match_id'       => 'Match ID',
			'map_name'       => 'Map Name',
			'score_1'        => 'Score 1',
			'score_2'        => 'Score 2',
			'current_side'   => 'Current Side',
			'status'         => 'Status',
			'maps_for'       => 'Maps For',
			'nb_ot'          => 'Nb Ot',
			'identifier_id'  => 'Identifier ID',
			'tv_record_file' => 'Tv Record File',
			'created_at'     => 'Created At',
			'updated_at'     => 'Updated At',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMatch () {
		return $this->hasOne(Matches::className(), ['id' => 'match_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMapsScores () {
		return $this->hasMany(MapsScore::className(), ['map_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMatches () {
		return $this->hasMany(Matches::className(), ['current_map' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPlayerKills () {
		return $this->hasMany(PlayerKill::className(), ['map_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPlayers () {
		return $this->hasMany(Players::className(), ['map_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPlayersHeatmaps () {
		return $this->hasMany(PlayersHeatmap::className(), ['map_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRounds () {
		return $this->hasMany(Round::className(), ['map_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRoundSummaries () {
		return $this->hasMany(RoundSummary::className(), ['map_id' => 'id']);
	}
}
