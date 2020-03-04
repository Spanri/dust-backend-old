<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_session".
 *
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property int $lifetime
 * @property int $last_activity
 *
 * @property User $user
 */
class UserSession extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_session';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'token', 'lifetime', 'last_activity'], 'required'],
            [['user_id', 'lifetime', 'last_activity'], 'default', 'value' => null],
            [['user_id', 'lifetime', 'last_activity'], 'integer'],
            [['token'], 'string', 'max' => 32],
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
            'token' => 'Token',
            'lifetime' => 'Lifetime',
            'last_activity' => 'Last Activity',
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
