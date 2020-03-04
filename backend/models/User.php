<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property int $billing_id
 * @property int $referral_program_id
 * @property int $user_session_id
 * @property string $email
 * @property string $username
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string|null $email_confirm_token
 * @property string $avatar
 * @property int $created_at
 * @property int|null $updated_at дата изменения
 *
 * @property ReferralProgram[] $referralPrograms
 * @property Transaction[] $transactions
 * @property UserAccountType[] $userAccountTypes
 * @property AccountType[] $accountTypes
 * @property UserSession[] $userSessions
 */
class User extends \yii\db\ActiveRecord
{
    private $idText;

   


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
             //check if value is a valid UUID
            ['id','match', 'pattern'=>'/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i'],
            // convert UUID from text value to binary and store the text value in a private variable,
            // this is a workaround for lack of mapping in active record
            ['id','filter','skipOnError' => true, 'filter' => function($uuid) {
                $this->idText = $uuid;
                return pack("H*", str_replace('-', '', $uuid));
            }],
            //now let's check if ID is taken
            ['id','unique','filter' => function(\yii\db\Query $q) {
                $q->where(['id' => $this->getAttribute('id')]);
            }],
            [['billing_id', 'referral_program_id', 'user_session_id', 'email', 'username', 'password_hash', 'avatar', 'created_at'], 'required'],
            [['billing_id', 'referral_program_id', 'user_session_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['billing_id', 'referral_program_id', 'user_session_id', 'created_at', 'updated_at'], 'integer'],
            [['email', 'username', 'password_hash', 'password_reset_token', 'email_confirm_token', 'avatar'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['username'], 'unique'],
        ];
    }

    public function __get($name)
    {
        return ($name === 'id') ? $this->getId() : parent::__get($name);
    }

    /**
     * Return UUID in a textual representation
     */
    public function getId(): string
    {
        if ($this->idText === NULL && $this->getIsNewRecord()){
            //the filter did not convert ID to binary yet, return the data from input
            return strtoupper($this->getAttribute('id'));
        }
        // ID is converted
        return strtoupper($this->idText ?? $this->getAttribute('id_text'));
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['id'] = function(){ return $this->getId(); };
        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'billing_id' => 'Billing ID',
            'referral_program_id' => 'Referral Program ID',
            'user_session_id' => 'User Session ID',
            'email' => 'Email',
            'username' => 'Username',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email_confirm_token' => 'Email Confirm Token',
            'avatar' => 'Avatar',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[ReferralPrograms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReferralPrograms()
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
     * Gets query for [[AccountTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAccountTypes()
    {
        return $this->hasMany(AccountType::className(), ['id' => 'account_type_id'])->viaTable('user_account_type', ['user_id' => 'id']);
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
