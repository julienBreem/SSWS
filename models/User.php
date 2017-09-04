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
            [['access_token', 'family_name', 'given_name', 'email','quote'], 'string', 'max' => 255],
            [['livesIn','from','plan'], 'string', 'max' => 100],
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
            'picture' => 'picture',
        ];
    }

    public function fields()
    {
        return[
            'id_user' => 'id_user',
            'family_name' => 'family_name',
            'given_name' => 'given_name',
            'email' => 'email',
            'quote' => 'quote',
            'livesIn' => 'livesIn',
            'from' => 'from',
            'plan' => 'plan',
            'picture' => function($model){
                return $model->getPicture();
            },
            'activeUser' => function($model){
                if($model->getPrimaryKey() == Yii::$app->user->getId()){
                    return true;
                } else {
                    return false;
                }
            },
            'isFollower' => function($model){
                return $model->isFollower();
            },
            'isFollowed' => function($model){
                return $model->isFollowed();
            },
            'spotsCount' => function($model){
                return count($model->spots);
            },
            'followersCount' => function($model){
                return count($model->followers);
            },
            'followingCount' => function($model){
                return count($model->followedList);
            },
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
    public function getIdentities()
    {
        return $this->hasMany(Identity::className(), ['ss_user_id' => 'id_user']);
    }

    public function getSpots()
    {
        return $this->hasMany(Spot::className(), ['ss_spots_id' => 'spot_id'])->viaTable('ss_users_spots', ['user_id' => 'id_user']);
    }

    public function getFollowers()
    {
        return $this->hasMany(User::className(), ['id_user' => 'follower_id'])->viaTable('ss_followers', ['followed_id' => 'id_user']);
    }

    public function getFollowedList()
    {
        return $this->hasMany(User::className(), ['id_user' => 'followed_id'])->viaTable('ss_followers', ['follower_id' => 'id_user']);
    }

    public function isFollower()
    {
        foreach($this->followedList as $followed){
            if($followed->getPrimaryKey() == Yii::$app->user->getId()){
                return true;
            }
        }
        return false;
    }

    public function isFollowed()
    {
        foreach($this->followers as $follower){
            if($follower->getPrimaryKey() == Yii::$app->user->getId()){
                return true;
            }
        }
        return false;
    }

    public function getPicture()
    {
        if($this->picture == "") return "http://rogersfoodsafetyiot.com/wp-content/uploads/2016/09/User-450x450.jpg";
        if(filter_var($this->picture, FILTER_VALIDATE_URL) === FALSE){
            return 'http://localhost/SSWS/web/images/'.$this->picture;
        }
        return $this->picture;
    }
}
