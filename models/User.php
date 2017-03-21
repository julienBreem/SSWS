<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


/**
 * This is the model class for table "ss_user".
 *
 * @property integer $id_user
 * @property string clientID
 * @property string $access_token
 * @property string $family_name
 * @property string $given_name
 * @property string $email
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_user';
    }
    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByClientId($ClientId)
    {
        return static::findOne(['clientID' => $ClientId]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id_user;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->access_token;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['access_token', 'family_name', 'given_name', 'clientID'], 'required'],
            [['access_token', 'family_name', 'given_name', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'clientID' => 'Client Id',
            'access_token' => 'Access Token',
            'family_name' => 'Family Name',
            'given_name' => 'Given Name',
            'email' => 'Email',
        ];
    }
}
