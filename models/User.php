<?php

namespace app\models;

use app\models\Users;

class User extends \yii\base\Object implements \yii\web\IdentityInterface {
	public $id;
	public $username;
	public $algorithm;
	public $password;
	public $salt;
	public $authKey;
	public $accessToken;


	/**
	 * @inheritdoc
	 */
	public static function findIdentity ($id) {
		$user = Users::findOne([$id]);
		if($user) {
			$user = [
				'id'          => $user->id, 'username' => $user->username, 'salt' => $user->salt,
				'algorithm'   => $user->algorithm, 'password' => $user->password, 'authKey' => 'zrsdhbntjn',
				'accessToken' => 'yfghkyful',
			];
		}
		return ($user)? new static($user) : null;
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken ($token, $type = null) {
		$session = Sessions::findOne(['remember_key' => $token]);
		if(!$session) return null;
		$user = Users::findOne([$session->user_id]);
		if($user) {
			$user = [
				'id'          => $user->id, 'username' => $user->username, 'salt' => $user->salt,
				'algorithm'   => $user->algorithm, 'password' => $user->password, 'authKey' => 'zrsdhbntjn',
				'accessToken' => 'yfghkyful',
			];
		}
		return ($user)? new static($user) : null;
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return static|null
	 */
	public static function findByUsername ($username) {
		$user = Users::findOne(['username' => $username]);
		if($user) {
			$user = [
				'id'          => $user->id,
				'username'    => $user->username,
				'salt'        => $user->salt,
				'algorithm'   => $user->algorithm,
				'password'    => $user->password,
				'authKey'     => 'zrsdhbntjn',
				'accessToken' => 'yfghkyful',
			];
		}
		return ($user)? new static($user) : null;
	}

	/**
	 * @inheritdoc
	 */
	public function getId () {
		return $this->id;
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey () {
		return $this->authKey;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey ($authKey) {
		return $this->authKey === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword ($password) {
		return $this->password === call_user_func_array($this->algorithm, array($this->salt . $password));
	}
}
