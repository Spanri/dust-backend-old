<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "articles".
 *
 * @property int $id
 * @property string|null $url url
 * @property string|null $name название
 * @property string|null $content
 * @property int|null $created_at
 */
class Articles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'articles';
    }

    // public function behaviors()
    // {
    //     return [
    //         [
    //         'class' => TimestampBehavior::class,
    //         'updatedAttribute' => false
    //     ]
    //     ];
    // }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['created_at'], 'default', 'value' => null],
            [['created_at'], 'integer'],
            [['url', 'name'], 'string', 'max' => 255],
            [['url'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'url',
            'name' => 'название',
            'content' => 'Content',
            'created_at' => 'Created At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\ArticlesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\ArticlesQuery(get_called_class());
    }
}
