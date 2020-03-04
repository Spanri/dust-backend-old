<?php

namespace app\controllers;

use yii\rest\ActiveController;

class ArticlesController extends ActiveController
{
  public $modelClass = 'app\models\Articles';
}