<?php

namespace app\modules\bot\controllers;

use app\models\AccountType;
use app\models\Unregistered;
use app\models\User;
use app\models\Transaction;
use app\models\StatusCode;
use app\modules\rotation\models\AgencyCampaign;
use app\modules\structure\wrappers\AgencyWrapper;
use Yii;
use yii\db\ActiveQuery;

/**
 * Class ArraBotController
 * @package app\controllers
 */
class ArraBotController extends \yii\web\Controller
{
//    public function beforeAction($action)
//    {
//        $this->enableCsrfValidation = true;
//        return parent::beforeAction($action);
//    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function actionCoinsList()
    {
        try {
            $unregisteredUsers = Unregistered::find()
                ->select('{{%unregistered}}.type, {{%unregistered}}.account_id, {{%unregistered}}.dust_token_num')
                ->all();

            $registeredUsers = User::find()
                ->select('user_account_type.type, user_account_type.account_id, billing.dust_token_num')
                ->leftJoin('user_account_type', '{{%user}}.id = {{%user_account_type}}.user_id')
                ->leftJoin('billing', '{{%user}}.id = {{%billing}}.user_id')
                ->asArray()
                ->all();

            return array_merge($unregisteredUsers, $registeredUsers);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param $type
     * @param $account_id
     * @return Unregistered|User|array|\yii\db\ActiveRecord|null
     * @throws \Exception
     */
    public function actionCoinsIndex($type, $account_id)
    {
        try {
            if (!$user = User::find()
                ->select('user_account_type.type, user_account_type.account_id, billing.dust_token_num')
                ->leftJoin('user_account_type', '{{%user}}.id = {{%user_account_type}}.user_id')
                ->leftJoin('billing', '{{%user}}.id = {{%billing}}.user_id')
                ->where(['{{%user_account_type}}.type' => $type, '{{%user_account_type}}.account_id' => $account_id])
                ->one()) {
                if (!$user = Unregistered::find()
                    ->select('{{%unregistered}}.type, {{%unregistered}}.account_id, {{%unregistered}}.dust_token_num')
                    ->where(['{{%unregistered}}.type' => $type, '{{%unregistered}}.account_id' => $account_id])
                    ->one()) {
                    $newAccount = new AccountType;
                    $newAccount->type = $type;
                    $newAccount->account_id = $account_id;
                    $newAccount->save();

                    $user = new Unregistered;
                    $user->type = $type;
                    $user->account_id = $account_id;
                    $user->dust_token_num = 0.000;
                    $user->save();
                }
            }
            return $user;
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param $type
     * @param $account_id
     * @return Unregistered|User|array|\yii\db\ActiveRecord|null
     * @throws \Exception
     */
    public function actionCoinsUpdate($type, $account_id)
//    $coins, $operation = 0
    {
        try {
            $coins = Yii::$app->request->post('coins');
            $operation = Yii::$app->request->post('operation', 0);

//            return [$coins, $operation];

            if (!$user = User::find()
                ->select('user_account_type.type, user_account_type.account_id, billing.dust_token_num')
                ->leftJoin('user_account_type', '{{%user}}.id = {{%user_account_type}}.user_id')
                ->leftJoin('billing', '{{%user}}.id = {{%billing}}.user_id')
                ->where(['{{%user_account_type}}.type' => $type, '{{%user_account_type}}.account_id' => $account_id])
                ->one()) {
                if (!$user = Unregistered::find()
                    ->select('{{%unregistered}}.type, {{%unregistered}}.account_id, {{%unregistered}}.dust_token_num')
                    ->where(['{{%unregistered}}.type' => $type, '{{%unregistered}}.account_id' => $account_id])
                    ->one()) {
                    $newAccount = new AccountType;
                    $newAccount->type = $type;
                    $newAccount->account_id = $account_id;
                    $newAccount->save();

                    $user = new Unregistered;
                    $user->type = $type;
                    $user->account_id = $account_id;
                    $user->dust_token_num = 0.000;
                    $user->save();
                }
            }

            // 0 - просто присвоить
            // 1 - прибавить
            // 2 - вычесть
            if (in_array($operation, [0, 1, 2])) {
                $user = Unregistered::findOne(['type' => $type, 'account_id' => $account_id]);
            }

            if ($operation == 0) {
                $user->dust_token_num = $coins;
            } elseif ($operation == 1) {
                $user->dust_token_num = $user->dust_token_num + $coins;
            } elseif ($operation == 2) {
                $user->dust_token_num = $user->dust_token_num - $coins;
            }

            $user->save();

            if (in_array($operation, [0, 1, 2])) {
                $user->save();

                // тип 0 - коины, 1 - рубли
                // статус - 0 присвоение, 1 прибавление, 2 вычитание (для незареганных), 10, 11, 12 - для зареганных
//                $sql = "insert into table {{%transaction}} values [$user->id, 0, 0, 7]";
//                Yii::app()->db->createCommand($sql)->execute();

                $newTransaction = new Transaction;
                $newTransaction->user_id = $user->id;
                $newTransaction->type = 0;
                $newTransaction->status = $operation;
                $newTransaction->currency_num = $coins;
                $newTransaction->created_at = time();
                $newTransaction->save();
                return $newTransaction;
            }

            return $user;
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

}