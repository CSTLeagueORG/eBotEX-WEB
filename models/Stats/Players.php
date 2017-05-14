<?php

	namespace app\models\Stats;

	use app\models\Matches\Matches;
	use app\models\Matches\Maps;
	use app\models\Matches\RoundSummary;
	use app\models\Matches\PlayersSnapshot;

	use Yii;

	/**
	 * This is the model class for table "players".
	 *
	 * @property string            $id
	 * @property string            $match_id
	 * @property string            $map_id
	 * @property string            $player_key
	 * @property string            $team
	 * @property string            $ip
	 * @property string            $steamid
	 * @property string            $first_side
	 * @property string            $current_side
	 * @property string            $pseudo
	 * @property string            $nb_kill
	 * @property string            $assist
	 * @property string            $death
	 * @property string            $point
	 * @property string            $hs
	 * @property string            $defuse
	 * @property string            $bombe
	 * @property string            $tk
	 * @property string            $nb1
	 * @property string            $nb2
	 * @property string            $nb3
	 * @property string            $nb4
	 * @property string            $nb5
	 * @property string            $nb1kill
	 * @property string            $nb2kill
	 * @property string            $nb3kill
	 * @property string            $nb4kill
	 * @property string            $nb5kill
	 * @property string            $pluskill
	 * @property string            $firstkill
	 * @property string            $created_at
	 * @property string            $updated_at
	 *
	 * @property PlayerKill[]      $playerKills
	 * @property PlayerKill[]      $playerKills0
	 * @property Maps              $map
	 * @property Matches           $match
	 * @property PlayersHeatmap[]  $playersHeatmaps
	 * @property PlayersHeatmap[]  $playersHeatmaps0
	 * @property PlayersSnapshot[] $playersSnapshots
	 * @property RoundSummary[]    $roundSummaries
	 */
	class Players extends \yii\db\ActiveRecord {
		/**
		 * @inheritdoc
		 */
		public static function tableName () {
			return 'players';
		}

		/**
		 * @inheritdoc
		 */
		public function rules () {
			return [
				[['match_id', 'map_id', 'created_at', 'updated_at'], 'required'],
				[
					[
						'match_id', 'map_id', 'nb_kill', 'assist', 'death', 'point', 'hs', 'defuse', 'bombe', 'tk',
						'nb1',
						'nb2', 'nb3', 'nb4', 'nb5', 'nb1kill', 'nb2kill', 'nb3kill', 'nb4kill', 'nb5kill', 'pluskill',
						'firstkill',
					], 'integer',
				],
				[['created_at', 'updated_at'], 'safe'],
				[
					['player_key', 'team', 'ip', 'steamid', 'first_side', 'current_side', 'pseudo'], 'string',
					'max' => 255,
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
				'id'           => 'ID',
				'match_id'     => 'Match ID',
				'map_id'       => 'Map ID',
				'player_key'   => 'Player Key',
				'team'         => 'Team',
				'ip'           => 'Ip',
				'steamid'      => 'Steamid',
				'first_side'   => 'First Side',
				'current_side' => 'Current Side',
				'pseudo'       => 'Pseudo',
				'nb_kill'      => 'Nb Kill',
				'assist'       => 'Assist',
				'death'        => 'Death',
				'point'        => 'Point',
				'hs'           => 'Hs',
				'defuse'       => 'Defuse',
				'bombe'        => 'Bombe',
				'tk'           => 'Tk',
				'nb1'          => 'Nb1',
				'nb2'          => 'Nb2',
				'nb3'          => 'Nb3',
				'nb4'          => 'Nb4',
				'nb5'          => 'Nb5',
				'nb1kill'      => 'Nb1kill',
				'nb2kill'      => 'Nb2kill',
				'nb3kill'      => 'Nb3kill',
				'nb4kill'      => 'Nb4kill',
				'nb5kill'      => 'Nb5kill',
				'pluskill'     => 'Pluskill',
				'firstkill'    => 'Firstkill',
				'created_at'   => 'Created At',
				'updated_at'   => 'Updated At',
			];
		}

		/**
		 * @return \yii\db\ActiveQuery
		 */
		public function getPlayerKills () {
			return $this->hasMany(PlayerKill::className(), ['killed_id' => 'id']);
		}

		/**
		 * @return \yii\db\ActiveQuery
		 */
		public function getPlayerKills0 () {
			return $this->hasMany(PlayerKill::className(), ['killer_id' => 'id']);
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

		/**
		 * @return \yii\db\ActiveQuery
		 */
		public function getPlayersHeatmaps () {
			return $this->hasMany(PlayersHeatmap::className(), ['attacker_id' => 'id']);
		}

		/**
		 * @return \yii\db\ActiveQuery
		 */
		public function getPlayersHeatmaps0 () {
			return $this->hasMany(PlayersHeatmap::className(), ['player_id' => 'id']);
		}

		/**
		 * @return \yii\db\ActiveQuery
		 */
		public function getPlayersSnapshots () {
			return $this->hasMany(PlayersSnapshot::className(), ['player_id' => 'id']);
		}

		/**
		 * @return \yii\db\ActiveQuery
		 */
		public function getRoundSummaries () {
			return $this->hasMany(RoundSummary::className(), ['best_killer' => 'id']);
		}
	}
