<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_spots".
 *
 * @property integer $ss_spots_id
 * @property string $ss_country_code
 * @property string $place_id
 * @property string $lat
 * @property string $lng
 * @property string $name
 * @property string $url
 * @property string $international_phone_number
 *
 * @property SsAddressComponent[] $ssAddressComponents
 * @property SsCategorySpots[] $ssCategorySpots
 * @property SsCategory[] $categories
 * @property SsCountries $ssCountryCode
 */
class Spot extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_spots';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ss_country_code', 'lat', 'lng', 'name'], 'required'],
            [['ss_country_code'], 'string', 'max' => 2],
            [['place_id', 'name'], 'string', 'max' => 100],
            [['lat', 'lng'], 'double'],
            [['url'], 'string', 'max' => 255],
            [['international_phone_number'], 'string', 'max' => 50],
            [['ss_country_code'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['ss_country_code' => 'COUNTRY_CODE']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ss_spots_id' => 'Ss Spots ID',
            'ss_country_code' => 'Ss Country Code',
            'place_id' => 'Place ID',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'name' => 'Name',
            'url' => 'Url',
            'international_phone_number' => 'International Phone Number',
        ];
    }
    public function fields()
    {
        return[
            'ss_spots_id' => 'ss_spots_id',
            'ss_country_code' => 'ss_country_code',
            'place_id' => 'place_id',
            'place_id' => 'place_id',
            'lat' => 'lat',
            'lng' => 'lng',
            'name' => 'name',
            'url' => 'url',
            'scope' => function(){ return "sharingSpots"; },
            'spotted' => function($model){
                return $model->spotted;
            },
            'spotCount' => function($model){
                return count($model->spotters);
            },
            'planned' => function($model){
                return $model->planned;
            },
            'addressComponent' => function($model){ return $model->addressComponents; },
            'country' => function($model){ return $model->country; },
            'cityName' => function($model){
                foreach($model->addressComponents as $comp){
                    if($comp->componentType->name == 'locality')return $comp->long_name;
                }
                foreach($model->addressComponents as $comp){
                    if($comp->componentType->name == 'sublocality_level_1')return $comp->long_name;
                }
                return 'unknown';
            },
            'types' => function($model){ return $model->categories; },
            'photos' => function($model){
                $photos = [];
                foreach($model->spotPhotos as $photo){
                    $photos[] = $photo->url;
                }
                return $photos;
            },
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddressComponents()
    {
        return $this->hasMany(AddressComponent::className(), ['spots_id' => 'ss_spots_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpotPhotos()
    {
        return $this->hasMany(SpotPhoto::className(), ['spot_id' => 'ss_spots_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['ID' => 'category_id'])->viaTable('ss_category_spots', ['spots_id' => 'ss_spots_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpotters()
    {
        return $this->hasMany(User::className(), ['id_user' => 'user_id'])->viaTable('ss_users_spots', ['spot_id' => 'ss_spots_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanners()
    {
        return $this->hasMany(User::className(), ['id_user' => 'user_id'])->viaTable('ss_users_spotlater', ['spot_id' => 'ss_spots_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['COUNTRY_CODE' => 'ss_country_code']);
    }/**
     * @return \yii\db\ActiveQuery
     */
    public function getSpotted()
    {
        foreach($this->spotters as $user){
            if($user->getPrimaryKey() == Yii::$app->user->getId()){
                return true;
            }
        }
        return false;
    }
    public function getPlanned()
    {
        foreach($this->planners as $user){
            if($user->getPrimaryKey() == Yii::$app->user->getId()){
                return true;
            }
        }
        return false;
    }
}
