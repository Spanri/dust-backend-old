<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $email
 * @property string $username
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string|null $email_confirm_token
 * @property int $status
 * @property string $avatar
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Billing[] $billings
 * @property ReferralProgram[] $referralPrograms
 * @property ReferralProgram[] $referralPrograms0
 * @property Transaction[] $transactions
 * @property UserAccountType[] $userAccountTypes
 * @property AccountType[] $types
 * @property UserSession[] $userSessions
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'email', 'username', 'password_hash', 'avatar'], 'required'],
            [['id'], 'string'],
            [['status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['email', 'username', 'password_hash', 'password_reset_token', 'email_confirm_token', 'avatar'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['username'], 'unique'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'username' => 'Username',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email_confirm_token' => 'Email Confirm Token',
            'status' => 'Status',
            'avatar' => 'Avatar',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Billings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBillings()
    {
        return $this->hasMany(Billing::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[ReferralPrograms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReferralPrograms()
    {
        return $this->hasMany(ReferralProgram::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[ReferralPrograms0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReferralPrograms0()
    {
        return $this->hasMany(ReferralProgram::className(), ['upline_user_id' => 'id']);
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserAccountTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserAccountTypes()
    {
        return $this->hasMany(UserAccountType::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Types]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTypes()
    {
        return $this->hasMany(AccountType::className(), ['type' => 'type', 'account_id' => 'account_id'])->viaTable('user_account_type', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserSessions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserSessions()
    {
        return $this->hasMany(UserSession::className(), ['user_id' => 'id']);
    }
}
