<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "account_type".
 *
 * @property int $type
 * @property string $account_id
 * @property string|null $username
 *
 * @property Unregistered[] $unregistereds
 * @property UserAccountType[] $userAccountTypes
 * @property User[] $users
 */
class AccountType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'account_id'], 'required'],
            [['type'], 'default', 'value' => null],
            [['type'], 'integer'],
            [['account_id', 'username'], 'string', 'max' => 32],
            [['type', 'account_id'], 'unique', 'targetAttribute' => ['type', 'account_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'type' => 'Type',
            'account_id' => 'Account ID',
            'username' => 'Username',
        ];
    }

    /**
     * Gets query for [[Unregistereds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnregistereds()
    {
        return $this->hasMany(Unregistered::className(), ['type' => 'type', 'account_id' => 'account_id']);
    }

    /**
     * Gets query for [[UserAccountTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserAccountTypes()
    {
        return $this->hasMany(UserAccountType::className(), ['type' => 'type', 'account_id' => 'account_id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_account_type', ['type' => 'type', 'account_id' => 'account_id']);
    }
}
