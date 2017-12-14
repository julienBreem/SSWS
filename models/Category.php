<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ss_category}}".
 *
 * @property integer $ID
 * @property string $category_name
 *
 * @property SsCategorySpots[] $ssCategorySpots
 * @property SsUsersSpots[] $spots
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ss_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'category_name'], 'required'],
            [['ID'], 'integer'],
            [['category_name'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'category_name' => 'Category Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSsCategorySpots()
    {
        return $this->hasMany(CategorySpots::className(), ['category_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpots()
    {
        return $this->hasMany(UsersSpots::className(), ['id' => 'spots_id'])->viaTable('{{%ss_category_spots}}', ['category_id' => 'ID']);
    }
}
