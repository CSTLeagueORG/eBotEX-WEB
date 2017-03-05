<?php

namespace app\models\Matches;

use Yii;

/**
 * This is the model class for table "maps_score".
 *
 * @property string $id
 * @property string $map_id
 * @property string $type_score
 * @property string $score1_side1
 * @property string $score1_side2
 * @property string $score2_side1
 * @property string $score2_side2
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Maps   $map
 */
class MapsScore extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return 'maps_score';
	}

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
			[['map_id', 'created_at', 'updated_at'], 'required'],
			[['map_id', 'score1_side1', 'score1_side2', 'score2_side1', 'score2_side2'], 'integer'],
			[['created_at', 'updated_at'], 'safe'],
			[['type_score'], 'string', 'max' => 255],
			[['map_id'], 'exist', 'skipOnError'     => true, 'targetClass' => Maps::className(),
			                      'targetAttribute' => ['map_id' => 'id'],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'id'           => Yii::t('app', 'ID'),
			'map_id'       => Yii::t('app', 'Map ID'),
			'type_score'   => Yii::t('app', 'Type Score'),
			'score1_side1' => Yii::t('app', 'Score1 Side1'),
			'score1_side2' => Yii::t('app', 'Score1 Side2'),
			'score2_side1' => Yii::t('app', 'Score2 Side1'),
			'score2_side2' => Yii::t('app', 'Score2 Side2'),
			'created_at'   => Yii::t('app', 'Created At'),
			'updated_at'   => Yii::t('app', 'Updated At'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMap () {
		return $this->hasOne(Maps::className(), ['id' => 'map_id']);
	}
}
