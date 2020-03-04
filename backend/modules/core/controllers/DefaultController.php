<?php

namespace app\modules\core\controllers;

use yii\web\Controller;

/**
 * Default controller for the `core` module
 */
class DefaultController extends Controller
{
    // public function behaviors()
    // {
    //     return [
    //         'slug' => [
    //         'class' => 'common\behaviors\Slug', //класс для поведения
    //         'iniciali' => 'post_', //переменная для класса
    //         ]
    //     ];
    // }
    
    // update `table` set `uuid` = UUID() 

    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
