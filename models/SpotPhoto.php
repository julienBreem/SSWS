<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_spot_photos".
 *
 * @property integer $spot_photos_id
 * @property integer $spot_id
 * @property string $url
 *
 * @property SsSpots $spot
 */
class SpotPhoto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_spot_photos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spot_id', 'url'], 'required'],
            [['spot_id'], 'integer'],
            [['url'], 'string', 'max' => 255],
            [['spot_id'], 'exist', 'skipOnError' => true, 'targetClass' => Spot::className(), 'targetAttribute' => ['spot_id' => 'ss_spots_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'spot_photos_id' => 'Spot Photos ID',
            'spot_id' => 'Spot ID',
            'url' => 'Url',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpot()
    {
        return $this->hasOne(Spot::className(), ['ss_spots_id' => 'spot_id']);
    }
}
