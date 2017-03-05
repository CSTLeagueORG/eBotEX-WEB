<?php

namespace app\models\Matches;

use app\models\Seasons\Seasons;

use Yii;

/**
 * This is the model class for table "advertising".
 *
 * @property string  $id
 * @property string  $season_id
 * @property string  $message
 * @property integer $active
 * @property string  $created_at
 * @property string  $updated_at
 *
 * @property Seasons $season
 */
class Advertising extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return 'advertising';
	}

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
			[['season_id', 'active'], 'integer'],
			[['message'], 'string'],
			[['created_at', 'updated_at'], 'required'],
			[['created_at', 'updated_at'], 'safe'],
			[
				['season_id'], 'exist', 'skipOnError'     => true, 'targetClass' => Seasons::className(),
				                        'targetAttribute' => ['season_id' => 'id'],
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
			'message'    => Yii::t('app', 'Message'),
			'active'     => Yii::t('app', 'Active'),
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
}
