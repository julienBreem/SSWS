<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_identities".
 *
 * @property integer $ss_user_id
 * @property string $user_id
 * @property string $provider
 * @property string $connection
 * @property integer $isSocial
 *
 * @property SsUsers $ssUser
 */
class Identity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_identities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'provider',], 'required'],
            [['ss_user_id', ], 'integer'],
            [['isSocial'], 'boolean'],
            [['user_id'], 'string', 'max' => 255],
            [['provider', 'connection'], 'string', 'max' => 100],
            [['ss_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['ss_user_id' => 'id_user']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ss_user_id' => 'Ss User ID',
            'user_id' => 'User ID',
            'provider' => 'Provider',
            'connection' => 'Connection',
            'isSocial' => 'Is Social',
        ];
    }

    /**
     * Finds an identity by the given Ids.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByIds($provider, $id)
    {
        return static::findOne(['provider' => $provider, 'user_id' => $id]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id_user' => 'ss_user_id']);
    }
}
