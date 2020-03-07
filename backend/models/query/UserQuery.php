<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Articles]].
 *
 * @see \app\models\Articles
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /**
     * Фильтрация по пользователю
     * @param null $id
     * @return UserQuery
     */
    public function byUser($id)
    {
        return $this->andWhere(['id' => $id]);
    }
}