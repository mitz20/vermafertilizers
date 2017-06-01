<?php

namespace app\models;

use Yii;
use app\models\Owner;

class User extends \yii\base\Object implements \yii\web\IdentityInterface {

    public $id;
    public $username;
    public $password;
    public $email;
    public $active;
    public $authKey;
    public $accessToken;
    private static $user;

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return isset(self::$user[$id]) ? new static(self::$user[$id]) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {

        if (self::$user['accessToken'] === $token) {
            return new static(self::$user);
        }
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        $user = Owner::find()
                ->where([
                    'username' => $username,
                    'active' => Yii::$app->params['status']['active']
                ])
                ->one();
        if ($user) {
            return new static($user->toArray());
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return $this->password === $password;
    }

}
