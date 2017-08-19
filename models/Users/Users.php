<?php

	namespace app\models\Users;

	use Yii;
	use ErrorException;

	/**
	 * This is the model class for table "users".
	 *
	 * @property string          $uid
	 * @property string          $steamid
	 * @property string          $nickname
	 * @property integer         $is_admin
	 * @property string          $country
	 * @property string          $name
	 * @property string          $last_name
	 */
	class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface {
		/**
		 * @inheritdoc
		 */
		public static function tableName () {
			return 'users';
		}

		/**
		 * @inheritdoc
		 */
		public function rules () {
			return [
				[['steamid', 'nickname', 'is_admin', 'country'], 'required'],
				[['is_admin'], 'integer'],
				[['steamid'], 'string', 'max' => 255],
				[['nickname'], 'string', 'max' => 25],
				[['country'], 'string', 'max' => 3],
				[['name', 'last_name'], 'string', 'max' => 50],
				[['steamid'], 'unique'],
			];
		}

		/**
		 * @inheritdoc
		 */
		public function attributeLabels () {
			return [
				'uid'       => Yii::t('app', 'Uid'),
				'steamid'   => Yii::t('app', 'Steamid'),
				'nickname'  => Yii::t('app', 'Nickname'),
				'is_admin'  => Yii::t('app', 'Is Admin'),
				'country'   => Yii::t('app', 'Country'),
				'name'      => Yii::t('app', 'Name'),
				'last_name' => Yii::t('app', 'Last Name'),
			];
		}

		/**
		 * @return \yii\db\ActiveQuery
		 *//*
	public function getSessionTokens () {
		return $this->hasMany(SessionTokens::className(), ['uid' => 'uid']);
	} */

		public static function ToCommunityID ($id) {
			if(preg_match('/^STEAM_/', $id)) {
				$parts = explode(':', $id);
				return bcadd(bcadd(bcmul($parts[2], '2'), '76561197960265728'), $parts[1]);
			} elseif(is_numeric($id) && strlen($id) < 16) {
				return bcadd($id, '76561197960265728');
			} else {
				return $id; // We have no idea what this is, so just return it.
			}
		}

		public static function ToSteamID ($id) {
			if(is_numeric($id) && strlen($id) >= 16) {
				$z = bcdiv(bcsub($id, '76561197960265728'), '2');
			} elseif(is_numeric($id)) {
				$z = bcdiv($id, '2'); // Actually new User ID format
			} else {
				return $id; // We have no idea what this is, so just return it.
			}
			$y = bcmod($id, '2');
			return 'STEAM_1:' . $y . ':' . floor($z);
		}

		/**
		 * @inheritdoc
		 */
		public static function findIdentity ($id) {
			if(Yii::$app->getSession()->has('user-' . $id)) {
				return new self(Yii::$app->getSession()->get('user-' . $id));
			} else {
				return self::findOne($id)? new self(self::findOne($id)->toArray()) : null;
			}
		}

		function randString ($pass_len = 50) {
			$allchars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$string = "";
			mt_srand((double) microtime() * 1000000);
			for($i = 0; $i < $pass_len; $i++) {
				$string .= $allchars{mt_rand(0, strlen($allchars) - 1)};
			}
			return $string;
		}

		/**
		 * @param \nodge\eauth\ServiceBase $service
		 * @return Users
		 * @throws ErrorException
		 */
		public static function findByEAuth ($service) {
			if(!$service->getIsAuthenticated()) {
				throw new ErrorException('EAuth user should be authenticated before creating identity.');
			}

			$user = self::findOne(['steamid' => self::ToSteamID($service->getAttribute('steamid'))]);

			if(!$user) {
				$user = new Users;
				$user->nickname = $service->getAttribute('name');
				$user->steamid = self::ToSteamID($service->getAttribute('steamid'));
				$user->country = $service->getAttribute('loccountrycode');
				$user->is_admin = (self::find()->count() == 0)? 1 : 0;
				$user->insert();
			}

			$id = $service->getId();
			$attributes = [
				'uid'         => $user->uid,
				'authKey'     => md5($service->getId()),
				'nickname'    => $user->nickname,
				'steamid'     => self::ToSteamID($user->steamid),
				'is_admin'    => $user->is_admin,
				'accessToken' => self::randString(127),
			];
//		$attributes['profile']['service'] = $service->getServiceName();
			Yii::$app->getSession()->set('user-' . $id, $attributes);
			return new self($attributes);
		}

		/**
		 * @inheritdoc
		 */
		public static function findIdentityByAccessToken ($token, $type = null) {
			$session = Sessions::findOne(['remember_key' => $token]);
			if(!$session) return null;
			$user = self::findOne([$session->user_id]);
			if($user) {
				$user = [
					'id'          => $user->uid,
					'nickname'    => $user->nickname,
					'steamid'     => $user->steamid,
					'is_admin'    => $user->is_admin,
					'authKey'     => 'zrsdhbntjn',
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
			$user = self::findOne(['nickname' => $username]);
			if($user) {
				$user = [
					'id'          => $user->uid,
					'nickname'    => $user->nickname,
					'steamid'     => $user->steamid,
					'is_admin'    => $user->is_admin,
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
			return $this->uid;
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
