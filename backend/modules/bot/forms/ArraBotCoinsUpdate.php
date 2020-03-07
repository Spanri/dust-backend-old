<?php

namespace app\modules\bot\forms;

use app\modules\core\base\Model;
use app\models\AccountType;
use app\models\Unregistered;
use app\models\User;
use app\models\Transaction;
/**
 * Модель ввода данных для кампании агентства
 */
class ArraBotCoinsUpdate extends Model
{
    const OPERATION_SET = 0;
    const OPERATION_ADD = 1;
    const OPERATION_SUB = 2;

    public $coins;
    public $operation;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['$coins', 'exist', 'targetClass' => AccountType::class, 'targetAttribute' => 'id'],
            ['$coins', 'exist', 'targetClass' => AccountType::class, 'targetAttribute' => 'id']
        ];
    }
}