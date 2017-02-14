<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_cities".
 *
 * @property integer $ID_GEONAME
 * @property string $city_name
 * @property string $NAME_NO_HTML
 * @property string $LATITUDE
 * @property string $LONGITUDE
 * @property string $COUNTRY_CODE
 * @property integer $new
 *
 * @property TblSpots[] $tblSpots
 * @property TblUsers[] $tblUsers
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_cities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID_GEONAME'], 'required'],
            [['ID_GEONAME', 'new'], 'integer'],
            [['LATITUDE', 'LONGITUDE'], 'number'],
            [['city_name', 'NAME_NO_HTML'], 'string', 'max' => 200],
            [['COUNTRY_CODE'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID_GEONAME' => 'Id  Geoname',
            'city_name' => 'City Name',
            'NAME_NO_HTML' => 'Name  No  Html',
            'LATITUDE' => 'Latitude',
            'LONGITUDE' => 'Longitude',
            'COUNTRY_CODE' => 'Country  Code',
            'new' => 'New',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSpots()
    {
        return $this->hasMany(TblSpots::className(), ['city_name' => 'ID_GEONAME']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUsers()
    {
        return $this->hasMany(TblUsers::className(), ['city_name' => 'ID_GEONAME']);
    }
}
