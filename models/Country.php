<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_countries".
 *
 * @property integer $ID
 * @property string $COUNTRY_CODE
 * @property string $country_name
 * @property string $image
 * @property string $NORTH
 * @property string $SOUTH
 * @property string $EAST
 * @property string $WEST
 * @property integer $ID_GEONAME
 *
 * @property TblSpots[] $tblSpots
 * @property TblUsers[] $tblUsers
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_countries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID_GEONAME'], 'integer'],
            [['COUNTRY_CODE'], 'string', 'max' => 2],
            [['country_name'], 'string', 'max' => 45],
            [['image'], 'string', 'max' => 100],
            [['NORTH', 'SOUTH', 'EAST', 'WEST'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'COUNTRY_CODE' => 'Country  Code',
            'country_name' => 'Country Name',
            'NORTH' => 'North',
            'SOUTH' => 'South',
            'EAST' => 'East',
            'WEST' => 'West',
            'ID_GEONAME' => 'Id  Geoname',
            'image' => 'image',
        ];
    }

}
