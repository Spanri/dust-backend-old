<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

// $connection = Yii::$app->db;
// $command = $connection->CreateCommand("SELECT * FROM client");
// $rows = $command->queryAll();
// var_dump($rows);
// exit();

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage and all vue.js pages
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
