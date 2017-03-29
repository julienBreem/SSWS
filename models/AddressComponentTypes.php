<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_address_component_types".
 *
 * @property integer $address_component_types_id
 * @property string $name
 *
 * @property SsAddressComponent[] $ssAddressComponents
 */
class AddressComponentTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_address_component_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'address_component_types_id' => 'Address Component Types ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSsAddressComponents()
    {
        return $this->hasMany(AddressComponent::className(), ['type' => 'address_component_types_id']);
    }
}
