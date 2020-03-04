<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "account_type".
 *
 * @property int $id
 * @property string $type
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
            [['type', 'account_id', 'username'], 'string', 'max' => 32],
            [['type'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
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
        return $this->hasMany(Unregistered::className(), ['account_type_id' => 'id']);
    }

    /**
     * Gets query for [[UserAccountTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserAccountTypes()
    {
        return $this->hasMany(UserAccountType::className(), ['account_type_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_account_type', ['account_type_id' => 'id']);
    }
}
