<?php

namespace app\models\Users;

use yii\db\ActiveRecord;

class Users extends ActiveRecord {
	static function tableName () {
		return 'sf_guard_user';
	}
}