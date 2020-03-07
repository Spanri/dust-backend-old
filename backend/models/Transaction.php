<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property string $id
 * @property string|null $registered_user_id
 * @property string|null $unregistered_user_id
 * @property int $type
 * @property int $status
 * @property int|null $currency_num
 * @property int|null $created_at
 *
 * @property Unregistered $unregisteredUser
 * @property User $registeredUser
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
            [['id', 'type', 'status'], 'required'],
            [['id', 'registered_user_id', 'unregistered_user_id'], 'string'],
            [['type', 'status', 'currency_num', 'created_at'], 'default', 'value' => null],
            [['type', 'status', 'currency_num', 'created_at'], 'integer'],
            [['id'], 'unique'],
            [['unregistered_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unregistered::className(), 'targetAttribute' => ['unregistered_user_id' => 'id']],
            [['registered_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['registered_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'registered_user_id' => 'Registered User ID',
            'unregistered_user_id' => 'Unregistered User ID',
            'type' => 'Type',
            'status' => 'Status',
            'currency_num' => 'Currency Num',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[UnregisteredUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnregisteredUser()
    {
        return $this->hasOne(Unregistered::className(), ['id' => 'unregistered_user_id']);
    }

    /**
     * Gets query for [[RegisteredUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegisteredUser()
    {
        return $this->hasOne(User::className(), ['id' => 'registered_user_id']);
    }
}
