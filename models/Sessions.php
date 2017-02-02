<?php

namespace app\models;

use yii\db\ActiveRecord;

class Sessions extends ActiveRecord {
	static function tableName () {
		return 'sf_guard_remember_key';
	}
}