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
 * @property CategorySpots[] $CategorySpots
 * @property Category[] $categories
 * @property TagsSpots[] $TagsSpots
 * @property Tag[] $tags
 * @property Spot $spot
 * @property User $user
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
            [['spot_id'], 'exist', 'skipOnError' => true, 'targetClass' => Spot::className(), 'targetAttribute' => ['spot_id' => 'ss_spots_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id_user']],
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
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['ID' => 'category_id'])->viaTable('ss_category_spots', ['spots_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['tag_id' => 'tag_id'])->viaTable('ss_tags_spots', ['users_spots_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpot()
    {
        return $this->hasOne(Spot::className(), ['ss_spots_id' => 'spot_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id_user' => 'user_id']);
    }
}
