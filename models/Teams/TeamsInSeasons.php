<?php

	namespace app\models\Teams;

	use app\models\Seasons\Seasons;

	use Yii;

	/**
	 * This is the model class for table "teams_in_seasons".
	 *
	 * @property string  $id
	 * @property string  $season_id
	 * @property string  $team_id
	 * @property string  $created_at
	 * @property string  $updated_at
	 *
	 * @property Seasons $season
	 * @property Teams   $team
	 */
	class TeamsInSeasons extends \yii\db\ActiveRecord {
		/**
		 * @inheritdoc
		 */
		public static function tableName () {
			return 'teams_in_seasons';
		}

		/**
		 * @inheritdoc
		 */
		public function rules () {
			return [
				[['season_id', 'team_id'], 'integer'],
				[['created_at', 'updated_at'], 'required'],
				[['created_at', 'updated_at'], 'safe'],
				[
					['season_id'], 'exist', 'skipOnError'     => true, 'targetClass' => Seasons::className(),
					                        'targetAttribute' => ['season_id' => 'id'],
				],
				[
					['team_id'], 'exist', 'skipOnError'     => true, 'targetClass' => Teams::className(),
					                      'targetAttribute' => ['team_id' => 'id'],
				],
			];
		}

		/**
		 * @inheritdoc
		 */
		public function attributeLabels () {
			return [
				'id'         => Yii::t('app', 'ID'),
				'season_id'  => Yii::t('app', 'Season ID'),
				'team_id'    => Yii::t('app', 'Team ID'),
				'created_at' => Yii::t('app', 'Created At'),
				'updated_at' => Yii::t('app', 'Updated At'),
			];
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
		public function getTeam () {
			return $this->hasOne(Teams::className(), ['id' => 'team_id']);
		}
	}
