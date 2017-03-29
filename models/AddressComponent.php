<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_address_component".
 *
 * @property integer $ss_address_component_id
 * @property string $long_name
 * @property string $short_name
 * @property integer $type
 * @property integer $spots_id
 *
 * @property Spot $spots
 * @property AddressComponentTypes $type0
 */
class AddressComponent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_address_component';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['long_name', 'short_name', 'type', 'spots_id'], 'required'],
            [['type', 'spots_id'], 'integer'],
            [['long_name', 'short_name'], 'string', 'max' => 255],
            [['spots_id'], 'exist', 'skipOnError' => true, 'targetClass' => Spot::className(), 'targetAttribute' => ['spots_id' => 'ss_spots_id']],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => AddressComponentTypes::className(), 'targetAttribute' => ['type' => 'address_component_types_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ss_address_component_id' => 'Ss Address Component ID',
            'long_name' => 'Long Name',
            'short_name' => 'Short Name',
            'type' => 'Type',
            'spots_id' => 'Spots ID',
        ];
    }

    public function fields()
    {
        return[
            'id' => 'ss_address_component_id',
            'long_name' => 'long_name',
            'short_name' => 'short_name',
            'type' => function($model){ return [$model->componentType->name]; },
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpots()
    {
        return $this->hasOne(Spot::className(), ['ss_spots_id' => 'spots_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComponentType()
    {
        return $this->hasOne(AddressComponentTypes::className(), ['address_component_types_id' => 'type']);
    }
}
