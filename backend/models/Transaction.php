<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property string $id
 * @property string $user_id
 * @property int $type
 * @property int $status
 * @property int|null $currency_num
 * @property int|null $created_at
 *
 * @property User $user
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'type', 'status'], 'required'],
            [['id', 'user_id'], 'string'],
            [['type', 'status', 'currency_num', 'created_at'], 'default', 'value' => null],
            [['type', 'status', 'currency_num', 'created_at'], 'integer'],
            [['id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Type',
            'status' => 'Status',
            'currency_num' => 'Currency Num',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
