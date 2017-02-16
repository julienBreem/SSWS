<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_spots".
 *
 * @property integer $SPOT_ID
 * @property string $spot_name
 * @property integer $city_name
 * @property integer $country_name
 * @property integer $category
 * @property string $phone
 * @property string $web
 * @property integer $checkin
 * @property string $last_spot
 * @property integer $new
 * @property string $address
 * @property string $address2
 * @property integer $zip
 * @property string $state
 * @property integer $stars
 * @property integer $user_ID
 *
 * @property SsMySpots[] $ssMySpots
 * @property SsReport[] $ssReports
 * @property SsTags[] $ssTags
 * @property SsUserSpotsbook[] $ssUserSpotsbooks
 * @property SsCategory $category0
 * @property SsCities $cityName
 * @property SsCountries $countryName
 */
class Spot extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_spots';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spot_name', 'city_name', 'country_name', 'category'], 'required'],
            [['spot_name', 'phone', 'web', 'address', 'address2', 'state'], 'string'],
            [['city_name', 'country_name', 'category', 'checkin', 'new', 'zip', 'stars', 'user_ID'], 'integer'],
            [['spot_name','city_name', 'country_name'], 'safe'],
            [['category'], 'exist', 'skipOnError' => false, 'targetClass' => Category::className(), 'targetAttribute' => ['category' => 'ID']],
            [['city_name'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_name' => 'ID_GEONAME']],
            [['country_name'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_name' => 'ID']],
        ];
    }
	
	public function fields()
	{
		return[
			'ss_id' => 'SPOT_ID',
			'international_phone_number' => 'phone',
			'name' => 'spot_name',
			'scope' => function(){ return 'SharingSpot'; },
			'address' => function ($model) {
				return [
					'countryName' => $model->country->country_name,
					'cityName' => $model->city->NAME_NO_HTML,
					'zipcode' => $model->zip,
					'address1' => $model->address,
					'address2' => $model->address2,
				];
			},
			
			'website' => 'web',
			'types' => function($model){ return [$model->types->category_name]; },
			
		];
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSsMySpots()
    {
        return $this->hasMany(SsMySpots::className(), ['SPOT_ID' => 'SPOT_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSsReports()
    {
        return $this->hasMany(SsReport::className(), ['spot_ID' => 'SPOT_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSsTags()
    {
        return $this->hasMany(SsTags::className(), ['SPOT_ID' => 'SPOT_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSsUserSpotsbooks()
    {
        return $this->hasMany(SsUserSpotsbook::className(), ['spot_name' => 'SPOT_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypes()
    {
        return $this->hasOne(Category::className(), ['ID' => 'category']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['ID_GEONAME' => 'city_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['ID' => 'country_name']);
    }
}
