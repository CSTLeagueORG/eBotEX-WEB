<?php

namespace app\models;

use yii\db\ActiveRecord;

class Users extends ActiveRecord {
	static function tableName () {
		return 'sf_guard_user';
	}
}