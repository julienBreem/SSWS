<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ss_tips}}".
 *
 * @property integer $ss_tips_id
 * @property integer $user_id
 * @property integer $spot_id
 * @property string $content
 * @property string $date
 *
 * @property SsSpots $spot
 * @property SsUsers $user
 */
class Tip extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ss_tips}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'spot_id', 'content'], 'required'],
            [['user_id', 'spot_id'], 'integer'],
            [['content'], 'string'],
            [['date'], 'safe'],
            [['spot_id'], 'exist', 'skipOnError' => true, 'targetClass' => SsSpots::className(), 'targetAttribute' => ['spot_id' => 'ss_spots_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => SsUsers::className(), 'targetAttribute' => ['user_id' => 'id_user']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ss_tips_id' => 'Ss Tips ID',
            'user_id' => 'User ID',
            'spot_id' => 'Spot ID',
            'content' => 'Content',
            'date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpot()
    {
        return $this->hasOne(SsSpots::className(), ['ss_spots_id' => 'spot_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(SsUsers::className(), ['id_user' => 'user_id']);
    }
}
