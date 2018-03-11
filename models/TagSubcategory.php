<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_tags_subcategory".
 *
 * @property integer $tag_subcategory_id
 * @property string $name
 *
 * @property SsTags[] $ssTags
 */
class TagSubcategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_tags_subcategory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_subcategory_id', 'name'], 'required'],
            [['tag_subcategory_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tag_subcategory_id' => 'Tag Subcategory ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['subcategory_id' => 'tag_subcategory_id']);
    }
}
