<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "billing".
 *
 * @property string $id
 * @property string $user_id
 * @property float $ruble_token_num
 * @property float $dust_token_num
 *
 * @property User $user
 */
class Billing extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'billing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'required'],
            [['id', 'user_id'], 'string'],
            [['ruble_token_num', 'dust_token_num'], 'number'],
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
            'ruble_token_num' => 'Ruble Token Num',
            'dust_token_num' => 'Dust Token Num',
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
