<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_account_type".
 *
 * @property string $user_id
 * @property int $type
 * @property string $account_id
 *
 * @property AccountType $type0
 * @property User $user
 */
class UserAccountType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_account_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'account_id'], 'required'],
            [['user_id'], 'string'],
            [['type'], 'default', 'value' => null],
            [['type'], 'integer'],
            [['account_id'], 'string', 'max' => 32],
            [['user_id', 'type', 'account_id'], 'unique', 'targetAttribute' => ['user_id', 'type', 'account_id']],
            [['type', 'account_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountType::className(), 'targetAttribute' => ['type' => 'type', 'account_id' => 'account_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'type' => 'Type',
            'account_id' => 'Account ID',
        ];
    }

    /**
     * Gets query for [[Type0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType0()
    {
        return $this->hasOne(AccountType::className(), ['type' => 'type', 'account_id' => 'account_id']);
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
