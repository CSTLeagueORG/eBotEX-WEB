<?php

namespace app\models\Stats;

use app\models\Matches\Matches;
use app\models\Matches\Maps;
use app\models\Matches\Round;

use Yii;

/**
* This is the model class for table "player_kill".
*
	* @property string $id
	* @property string $match_id
	* @property string $map_id
	* @property string $killer_name
	* @property string $killer_id
	* @property string $killer_team
	* @property string $killed_name
	* @property string $killed_id
	* @property string $killed_team
	* @property string $weapon
	* @property integer $headshot
	* @property string $round_id
	* @property string $created_at
	* @property string $updated_at
	*
			* @property Players $killed
			* @property Players $killer
			* @property Maps $map
			* @property Matches $match
			* @property Round[] $rounds
	*/
class PlayerKill extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'player_kill';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['match_id', 'map_id', 'created_at', 'updated_at'], 'required'],
            [['match_id', 'map_id', 'killer_id', 'killed_id', 'headshot', 'round_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['killer_name', 'killed_name', 'weapon'], 'string', 'max' => 100],
            [['killer_team', 'killed_team'], 'string', 'max' => 20],
            [['killed_id'], 'exist', 'skipOnError' => true, 'targetClass' => Players::className(), 'targetAttribute' => ['killed_id' => 'id']],
            [['killer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Players::className(), 'targetAttribute' => ['killer_id' => 'id']],
            [['map_id'], 'exist', 'skipOnError' => true, 'targetClass' => Maps::className(), 'targetAttribute' => ['map_id' => 'id']],
            [['match_id'], 'exist', 'skipOnError' => true, 'targetClass' => Matches::className(), 'targetAttribute' => ['match_id' => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
	'id' => Yii::t('app', 'ID'),
	'match_id' => Yii::t('app', 'Match ID'),
	'map_id' => Yii::t('app', 'Map ID'),
	'killer_name' => Yii::t('app', 'Killer Name'),
	'killer_id' => Yii::t('app', 'Killer ID'),
	'killer_team' => Yii::t('app', 'Killer Team'),
	'killed_name' => Yii::t('app', 'Killed Name'),
	'killed_id' => Yii::t('app', 'Killed ID'),
	'killed_team' => Yii::t('app', 'Killed Team'),
	'weapon' => Yii::t('app', 'Weapon'),
	'headshot' => Yii::t('app', 'Headshot'),
	'round_id' => Yii::t('app', 'Round ID'),
	'created_at' => Yii::t('app', 'Created At'),
	'updated_at' => Yii::t('app', 'Updated At'),
];
}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getKilled()
	{
	return $this->hasOne(Players::className(), ['id' => 'killed_id']);
	}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getKiller()
	{
	return $this->hasOne(Players::className(), ['id' => 'killer_id']);
	}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getMap()
	{
	return $this->hasOne(Maps::className(), ['id' => 'map_id']);
	}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getMatch()
	{
	return $this->hasOne(Matches::className(), ['id' => 'match_id']);
	}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getRounds()
	{
	return $this->hasMany(Round::className(), ['kill_id' => 'id']);
	}
}
