<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "ss_users".
 *
 * @property integer $id_user
 * @property string $access_token
 * @property string $family_name
 * @property string $given_name
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 * @property string $last_ip
 * @property string $last_login
 *
 * @property SsIdentities[] $ssIdentities
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['access_token', 'family_name', 'given_name', 'created_at',], 'required'],
            [['access_token', 'family_name', 'given_name', 'email'], 'string', 'max' => 255],
            [['created_at', 'updated_at', 'last_ip', 'last_login'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'access_token' => 'Access Token',
            'family_name' => 'Family Name',
            'given_name' => 'Given Name',
            'email' => 'Email',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_ip' => 'Last Ip',
            'last_login' => 'Last Login',
        ];
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
     * @return \yii\db\ActiveQuery
     */
    public function getSsIdentities()
    {
        return $this->hasMany(Identity::className(), ['ss_user_id' => 'id_user']);
    }
}
