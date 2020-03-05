<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "unregistered".
 *
 * @property string $id
 * @property int $type
 * @property string $account_id
 * @property float $dust_coin_num
 *
 * @property AccountType $type0
 */
class Unregistered extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unregistered';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'account_id'], 'required'],
            [['id'], 'string'],
            [['type'], 'default', 'value' => null],
            [['type'], 'integer'],
            [['dust_coin_num'], 'number'],
            [['account_id'], 'string', 'max' => 32],
            [['id'], 'unique'],
            [['type', 'account_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountType::className(), 'targetAttribute' => ['type' => 'type', 'account_id' => 'account_id']],
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
            'dust_coin_num' => 'Dust Coin Num',
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
}
