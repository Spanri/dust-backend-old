<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Articles]].
 *
 * @see \app\models\Articles
 */
class ArticlesQuery extends \yii\db\ActiveQuery
{
    /**
     * Фильтрация по Url
     */
    public function byUrl($url)
    {
        return $this->andWhere(['url' => $url]);        
    }
}
