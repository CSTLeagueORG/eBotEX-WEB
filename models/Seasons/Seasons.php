<?php

namespace app\models\Seasons;

use app\models\Matches\Advertising;
use app\models\Matches\Matches;
use app\models\Teams\TeamsInSeasons;

use Yii;

/**
 * This is the model class for table "seasons".
 *
 * @property string           $id
 * @property string           $name
 * @property string           $event
 * @property string           $start
 * @property string           $end
 * @property string           $link
 * @property string           $logo
 * @property integer          $active
 * @property string           $created_at
 * @property string           $updated_at
 *
 * @property Advertising[]    $advertisings
 * @property Matches[]        $matches
 * @property TeamsInSeasons[] $teamsInSeasons
 */
class Seasons extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return 'seasons';
	}

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
			[['name', 'event', 'start', 'end', 'created_at', 'updated_at'], 'required'],
			[['start', 'end', 'created_at', 'updated_at'], 'safe'],
			[['active'], 'integer'],
			[['name', 'event'], 'string', 'max' => 50],
			[['link'], 'string', 'max' => 100],
			[['logo'], 'string', 'max' => 255],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'id'         => 'ID',
			'name'       => 'Season name',
			'event'      => 'Event',
			'start'      => 'Start',
			'end'        => 'End',
			'link'       => 'Link',
			'logo'       => 'Logo',
			'active'     => 'Active',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAdvertisings () {
		return $this->hasMany(Advertising::className(), ['season_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMatches () {
		return $this->hasMany(Matches::className(), ['season_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTeamsInSeasons () {
		return $this->hasMany(TeamsInSeasons::className(), ['season_id' => 'id']);
	}
}
