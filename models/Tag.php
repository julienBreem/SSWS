<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_tags".
 *
 * @property integer $tag_id
 * @property string $tag_name
 * @property integer $category_id
 *
 * @property TagsSpots[] $tagsSpots
 * @property UsersSpots[] $usersSpots
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_name', 'category_id'], 'required'],
            [['tag_name'], 'string'],
            [['category_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => 'Tag ID',
            'tag_name' => 'Tag Name',
            'category_id' => 'Category ID',
        ];
    }

    public function fields()
    {
        return [
            'tag_id' => 'tag_id',
            'tag_name' => 'tag_name',
            'category' => function ($model) {
                return $model->category->category_name;
            },
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['ID' => 'category_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagsSpots()
    {
        return $this->hasMany(TagsSpots::className(), ['tag_id' => 'tag_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersSpots()
    {
        return $this->hasMany(UsersSpots::className(), ['id' => 'users_spots_id'])->viaTable('ss_tags_spots', ['tag_id' => 'tag_id']);
    }
}
