<?php

namespace app\controllers;

use app\models\Articles;
use Yii;
use yii\web\Controller;

// $connection = Yii::$app->db;
// $command = $connection->CreateCommand("SELECT * FROM client");
// $rows = $command->queryAll();
// var_dump($rows);
// exit();

class SiteController extends Controller
{
    const BBB_AA = 1;

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

    public function actionView($url)
    {
        if(!$article = Articles::find()->byUrl($url)->one()){
            throw new \Exception('нет такой статьи');
        }

        $a = '100';
        $b = intval($a);

        $c = (int)$a;








        return $this->render('view', ['article' => $article]);
    }

}
