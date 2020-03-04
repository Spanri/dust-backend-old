<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "billing".
 *
 * @property int $id
 * @property int|null $ruble_token_num
 * @property int|null $dust_token_num
 */
class Billing extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'billing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ruble_token_num', 'dust_token_num'], 'default', 'value' => null],
            [['ruble_token_num', 'dust_token_num'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ruble_token_num' => 'Ruble Token Num',
            'dust_token_num' => 'Dust Token Num',
        ];
    }
}
