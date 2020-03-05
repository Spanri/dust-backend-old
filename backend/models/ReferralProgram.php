<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "referral_program".
 *
 * @property string $id
 * @property string $user_id
 * @property string|null $upline_user_id
 * @property int $level
 * @property int|null $upline_user_level
 * @property int|null $updated_at
 *
 * @property User $user
 * @property User $uplineUser
 */
class ReferralProgram extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'referral_program';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'required'],
            [['id', 'user_id', 'upline_user_id'], 'string'],
            [['level', 'upline_user_level', 'updated_at'], 'default', 'value' => null],
            [['level', 'upline_user_level', 'updated_at'], 'integer'],
            [['id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['upline_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['upline_user_id' => 'id']],
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
            'upline_user_id' => 'Upline User ID',
            'level' => 'Level',
            'upline_user_level' => 'Upline User Level',
            'updated_at' => 'Updated At',
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

    /**
     * Gets query for [[UplineUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUplineUser()
    {
        return $this->hasOne(User::className(), ['id' => 'upline_user_id']);
    }
}
