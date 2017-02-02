<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "teams".
 *
 * @property string           $id
 * @property string           $name
 * @property string           $shorthandle
 * @property string           $flag
 * @property string           $link
 * @property string           $created_at
 * @property string           $updated_at
 *
 * @property Matches[]        $matches
 * @property Matches[]        $matches0
 * @property TeamsInSeasons[] $teamsInSeasons
 */
class Teams extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return 'teams';
	}

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
			[['name', 'shorthandle', 'flag', 'created_at', 'updated_at'], 'required'],
			[['created_at', 'updated_at'], 'safe'],
			[['name', 'shorthandle'], 'string', 'max' => 50],
			[['flag'], 'string', 'max' => 2],
			[['link'], 'string', 'max' => 100],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'id'          => 'ID',
			'name'        => 'Name',
			'shorthandle' => 'Shorthandle',
			'flag'        => 'Flag',
			'link'        => 'Link',
			'created_at'  => 'Created At',
			'updated_at'  => 'Updated At',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMatches () {
		return $this->hasMany(Matches::className(), ['team_a' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMatches0 () {
		return $this->hasMany(Matches::className(), ['team_b' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTeamsInSeasons () {
		return $this->hasMany(TeamsInSeasons::className(), ['team_id' => 'id']);
	}
}
