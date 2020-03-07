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
                ->select('*')
                ->all();

            $registeredUsers = User::find()
                ->select('user.id, user_account_type.type, user_account_type.account_id, billing.dust_token_num')
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
            $registered_user = User::find()
                ->select('user.id, user_account_type.type, user_account_type.account_id, billing.dust_token_num')
                ->leftJoin('user_account_type', '{{%user}}.id = {{%user_account_type}}.user_id')
                ->leftJoin('billing', '{{%user}}.id = {{%billing}}.user_id')
                ->where(['{{%user_account_type}}.type' => $type, '{{%user_account_type}}.account_id' => $account_id])
                ->one();
            $unregistered_user = Unregistered::findOne(['type' => $type, 'account_id' => $account_id]);

            if ($registered_user == null && $unregistered_user == null) {
                $newAccount = new AccountType;
                $newAccount->type = $type;
                $newAccount->account_id = $account_id;
                $newAccount->save();

                Yii::$app->db->createCommand()
                    ->batchInsert('unregistered',
                        ['type', 'account_id', 'dust_token_num'],
                        [[$type, $account_id, 0.000]])->execute();

                $unregistered_user = Unregistered::findOne(['type' => $type, 'account_id' => $account_id]);
                return $unregistered_user;
            }

            if ($registered_user != null) {
                return $registered_user;
            }

            if ($unregistered_user != null) {
                return $unregistered_user;
            }

            throw new \Exception('Что-то пошло не так');
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
    {
        try {
            $coins = Yii::$app->request->post('coins');
            $operation = Yii::$app->request->post('operation', 0);

            $registered_user = User::find()
                ->select('user.id, user_account_type.type, user_account_type.account_id, billing.dust_token_num')
                ->leftJoin('user_account_type', '{{%user}}.id = {{%user_account_type}}.user_id')
                ->leftJoin('billing', '{{%user}}.id = {{%billing}}.user_id')
                ->where(['{{%user_account_type}}.type' => $type, '{{%user_account_type}}.account_id' => $account_id])
                ->one();
            $unregistered_user = Unregistered::findOne(['type' => $type, 'account_id' => $account_id]);

            if ($registered_user == null && $unregistered_user == null) {
                $newAccount = new AccountType;
                $newAccount->type = $type;
                $newAccount->account_id = $account_id;
                $newAccount->save();

                Yii::$app->db->createCommand()
                    ->batchInsert('unregistered',
                        ['type', 'account_id', 'dust_token_num'],
                        [[$type, $account_id, 0.000]])->execute();

                $unregistered_user = Unregistered::findOne(['type' => $type, 'account_id' => $account_id]);
            }

            // 0 - просто присвоить
            // 1 - прибавить
            // 2 - вычесть
            if ($unregistered_user != null && in_array($operation, [0, 1, 2])) {
                if ($operation == 0) {
                    $unregistered_user->dust_token_num = $coins;
                } elseif ($operation == 1) {
                    $unregistered_user->dust_token_num = $unregistered_user->dust_token_num + $coins;
                } elseif ($operation == 2) {
                    $unregistered_user->dust_token_num = $unregistered_user->dust_token_num - $coins;
                }

                $unregistered_user->save();
                // тип 0 - коины, 1 - рубли
                // статус - 0 присвоение, 1 прибавление, 2 вычитание (для незареганных), 10, 11, 12 - для зареганных
                Yii::$app->db->createCommand()
                    ->batchInsert('transaction',
                        ['unregistered_user_id', 'type', 'status', 'currency_num', 'created_at'],
                        [[$unregistered_user->id, 0, $operation, $coins, time()]])
                    ->execute();

                return $unregistered_user;
            }

            // 0 - просто присвоить
            // 1 - прибавить
            // 2 - вычесть
            if ($registered_user != null && in_array($operation, [0, 1, 2])) {
                if ($operation == 0) {
                    $registered_user->dust_token_num = $coins;
                } elseif ($operation == 1) {
                    $registered_user->dust_token_num = $registered_user->dust_token_num + $coins;
                } elseif ($operation == 2) {
                    $registered_user->dust_token_num = $registered_user->dust_token_num - $coins;
                }

                $registered_user->save();
                // тип 0 - коины, 1 - рубли
                // статус - 0 присвоение, 1 прибавление, 2 вычитание (для незареганных), 10, 11, 12 - для зареганных
                Yii::$app->db->createCommand()
                    ->batchInsert('transaction',
                        ['registered_user_id', 'type', 'status', 'currency_num', 'created_at'],
                        [[$registered_user->id, 0, $operation, $coins, time()]])
                    ->execute();
                return $registered_user;
            }

            throw new \Exception('Что-то пошло не так');
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

}