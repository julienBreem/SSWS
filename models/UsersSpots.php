<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_users_spots".
 *
 * @property integer $id
 * @property integer $spot_id
 * @property integer $user_id
 * @property string $date
 *
 * @property SsCategorySpots[] $ssCategorySpots
 * @property SsCategory[] $categories
 * @property SsSpots $spot
 * @property SsUsers $user
 */
class UsersSpots extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_users_spots';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spot_id', 'user_id'], 'required'],
            [['spot_id', 'user_id'], 'integer'],
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
            'id' => 'ID',
            'spot_id' => 'Spot ID',
            'user_id' => 'User ID',
            'date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategorySpots()
    {
        return $this->hasMany(CategorySpots::className(), ['spots_id' => 'id'])->inverseOf('spots');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['ID' => 'category_id'])->viaTable('ss_category_spots', ['spots_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpot()
    {
        return $this->hasOne(SsSpots::className(), ['ss_spots_id' => 'spot_id'])->inverseOf('usersSpots');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(SsUsers::className(), ['id_user' => 'user_id'])->inverseOf('usersSpots');
    }
}
