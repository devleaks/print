<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property integer $confirmed_at
 * @property string $unconfirmed_email
 * @property integer $blocked_at
 * @property string $role
 * @property integer $registration_ip
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $flags
 *
 * @property Profile $profile
 * @property SocialAccount[] $socialAccounts
 * @property Token[] $tokens
 * @property Work[] $works
 * @property WorkLine[] $workLines
 *
 * php yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations
 *
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorks()
    {
        return $this->hasMany(Work::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkLines()
    {
        return $this->hasMany(WorkLine::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getList()
    {
		return ArrayHelper::map(User::find()->orderBy('username')->asArray()->all(), 'id', 'username');
    }


	public static function getRole() {
		$role = null;
		if(isset(Yii::$app->user))
			if(isset(Yii::$app->user->identity))
				if(isset(Yii::$app->user->identity->role))
					$role = Yii::$app->user->identity->role;
		return $role;
	}

	public static function hasRole($role) {
		return is_array($role) ?
			in_array(User::getRole(), $role)
			:
			User::getRole() == $role
			;
	}

	public static function is($user) {
		if(isset(Yii::$app->user))
			if(isset(Yii::$app->user->identity))
				if(isset(Yii::$app->user->identity->username))
					if(Yii::$app->user->identity->username == $user)
						return true;
		return false;
	}
}
