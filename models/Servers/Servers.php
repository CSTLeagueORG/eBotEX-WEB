<?php

	namespace app\models\Servers;

	use app\models\Matches\Matches;

	use Yii;

	/**
	 * This is the model class for table "servers".
	 *
	 * @property string    $id
	 * @property string    $ip
	 * @property string    $rcon
	 * @property string    $hostname
	 * @property string    $tv_ip
	 * @property string    $created_at
	 * @property string    $updated_at
	 *
	 * @property Matches[] $matches
	 */
	class Servers extends \yii\db\ActiveRecord {
		/**
		 * @inheritdoc
		 */
		public static function tableName () {
			return 'servers';
		}

		/**
		 * @inheritdoc
		 */
		public function rules () {
			return [
				[['ip', 'rcon', 'hostname', 'created_at', 'updated_at'], 'required'],
				[['created_at', 'updated_at'], 'safe'],
				[['ip', 'rcon'], 'string', 'max' => 50],
				[['hostname', 'tv_ip'], 'string', 'max' => 100],
			];
		}

		/**
		 * @inheritdoc
		 */
		public function attributeLabels () {
			return [
				'id'         => 'ID',
				'ip'         => 'IP',
				'rcon'       => 'Rcon Password',
				'hostname'   => 'Name',
				'tv_ip'      => 'GOTV IP',
				'created_at' => 'Created At',
				'updated_at' => 'Updated At',
			];
		}

		/**
		 * @return \yii\db\ActiveQuery
		 */
		public function getMatches () {
			return $this->hasMany(Matches::className(), ['server_id' => 'id']);
		}
	}
