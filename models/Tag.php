<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_tags".
 *
 * @property integer $tag_id
 * @property string $tag_name
 * @property integer $subcategory_id
 *
 * @property TagSubcategory $subcategory
 * @property Category[] $categories
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
            [['tag_name', 'subcategory_id'], 'required'],
            [['tag_name'], 'string'],
            [['subcategory_id'], 'integer'],
            [['subcategory_id'], 'exist', 'skipOnError' => true, 'targetClass' => TagSubcategory::className(), 'targetAttribute' => ['subcategory_id' => 'tag_subcategory_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tag_id' => 'Tag ID',
            'tag_name' => 'Tag Name',
            'subcategory_id' => 'Subcategory ID',
        ];
    }

    public function fields()
    {
        return [
            'tag_id' => 'tag_id',
            'tag_name' => 'tag_name',
            'subcategories' => function ($model) {
                return $model->subcategory;
            },
            'categories' => function ($model) {
                return $model->categories;
            },
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubcategory()
    {
        return $this->hasOne(TagSubcategory::className(), ['tag_subcategory_id' => 'subcategory_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['ID' => 'category_id'])->viaTable('ss_tags_category', ['tag_id' => 'tag_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersSpots()
    {
        return $this->hasMany(UsersSpots::className(), ['id' => 'users_spots_id'])->viaTable('ss_tags_spots', ['tag_id' => 'tag_id']);
    }
}
