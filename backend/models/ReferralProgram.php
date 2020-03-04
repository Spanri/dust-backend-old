<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "referral_program".
 *
 * @property int $id
 * @property int $upline_user_id
 * @property int $level
 * @property int $upline_user_level
 * @property int|null $updated_at
 *
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
            [['upline_user_id', 'level', 'upline_user_level'], 'required'],
            [['upline_user_id', 'level', 'upline_user_level', 'updated_at'], 'default', 'value' => null],
            [['upline_user_id', 'level', 'upline_user_level', 'updated_at'], 'integer'],
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
            'upline_user_id' => 'Upline User ID',
            'level' => 'Level',
            'upline_user_level' => 'Upline User Level',
            'updated_at' => 'Updated At',
        ];
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
