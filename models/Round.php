<?php

namespace app\models;

use Yii;

/**
* This is the model class for table "round".
*
	* @property string $id
	* @property string $match_id
	* @property string $map_id
	* @property string $event_name
	* @property string $event_text
	* @property string $event_time
	* @property string $kill_id
	* @property string $round_id
	* @property string $created_at
	* @property string $updated_at
	*
			* @property PlayerKill $kill
			* @property Maps $map
			* @property Matchs $match
	*/
class Round extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'round';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['match_id', 'map_id', 'created_at', 'updated_at'], 'required'],
            [['match_id', 'map_id', 'event_time', 'kill_id', 'round_id'], 'integer'],
            [['event_text'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['event_name'], 'string', 'max' => 255],
            [['kill_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlayerKill::className(), 'targetAttribute' => ['kill_id' => 'id']],
            [['map_id'], 'exist', 'skipOnError' => true, 'targetClass' => Maps::className(), 'targetAttribute' => ['map_id' => 'id']],
            [['match_id'], 'exist', 'skipOnError' => true, 'targetClass' => Matchs::className(), 'targetAttribute' => ['match_id' => 'id']],
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
	'event_text' => Yii::t('app', 'Event Text'),
	'event_time' => Yii::t('app', 'Event Time'),
	'kill_id' => Yii::t('app', 'Kill ID'),
	'round_id' => Yii::t('app', 'Round ID'),
	'created_at' => Yii::t('app', 'Created At'),
	'updated_at' => Yii::t('app', 'Updated At'),
];
}

	/**
	* @return \yii\db\ActiveQuery
	*/
	public function getKill()
	{
	return $this->hasOne(PlayerKill::className(), ['id' => 'kill_id']);
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
}
