<?php

namespace app\models;

use Yii;

/**
* This is the model class for table "players_heatmap".
*
	* @property string $id
	* @property string $match_id
	* @property string $map_id
	* @property string $event_name
	* @property double $event_x
	* @property double $event_y
	* @property double $event_z
	* @property string $player_name
	* @property string $player_id
	* @property string $player_team
	* @property double $attacker_x
	* @property double $attacker_y
	* @property double $attacker_z
	* @property string $attacker_name
	* @property string $attacker_id
	* @property string $attacker_team
	* @property string $round_id
	* @property string $round_time
	* @property string $created_at
	* @property string $updated_at
	*
			* @property Players $attacker
			* @property Maps $map
			* @property Matchs $match
			* @property Players $player
	*/
class PlayersHeatmap extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'players_heatmap';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['match_id', 'map_id', 'created_at', 'updated_at'], 'required'],
            [['match_id', 'map_id', 'player_id', 'attacker_id', 'round_id', 'round_time'], 'integer'],
            [['event_x', 'event_y', 'event_z', 'attacker_x', 'attacker_y', 'attacker_z'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['event_name'], 'string', 'max' => 50],
            [['player_name', 'attacker_name'], 'string', 'max' => 255],
            [['player_team', 'attacker_team'], 'string', 'max' => 20],
            [['attacker_id'], 'exist', 'skipOnError' => true, 'targetClass' => Players::className(), 'targetAttribute' => ['attacker_id' => 'id']],
            [['map_id'], 'exist', 'skipOnError' => true, 'targetClass' => Maps::className(), 'targetAttribute' => ['map_id' => 'id']],
            [['match_id'], 'exist', 'skipOnError' => true, 'targetClass' => Matchs::className(), 'targetAttribute' => ['match_id' => 'id']],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => Players::className(), 'targetAttribute' => ['player_id' => 'id']],
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
	'event_name' => Yii::t('app', 'Event Name'),
	'event_x' => Yii::t('app', 'Event X'),
	'event_y' => Yii::t('app', 'Event Y'),
	'event_z' => Yii::t('app', 'Event Z'),
	'player_name' => Yii::t('app', 'Player Name'),
	'player_id' => Yii::t('app', 'Player ID'),
	'player_team' => Yii::t('app', 'Player Team'),
	'attacker_x' => Yii::t('app', 'Attacker X'),
	'attacker_y' => Yii::t('app', 'Attacker Y'),
	'attacker_z' => Yii::t('app', 'Attacker Z'),
	'attacker_name' => Yii::t('app', 'Attacker Name'),
	'attacker_id' => Yii::t('app', 'Attacker ID'),
	'attacker_team' => Yii::t('app', 'Attacker Team'),
	'round_id' => Yii::t('app', 'Round ID'),
	'round_time' => Yii::t('app', 'Round Time'),
	'created_at' => Yii::t('app', 'Created At'),
	'updated_at' => Yii::t('app', 'Updated At'),
];
}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getAttacker()
	{
	return $this->hasOne(Players::className(), ['id' => 'attacker_id']);
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
	return $this->hasOne(Matchs::className(), ['id' => 'match_id']);
	}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getPlayer()
	{
	return $this->hasOne(Players::className(), ['id' => 'player_id']);
	}
}
