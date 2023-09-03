<?php


namespace app\controllers\api;


use app\models\Categories;
use app\models\Friends;
use app\models\Users;
use Yii;

class CategoriesController extends ApiBaseController
{
    public function actionGetAll()
    {
        $categories=Categories::find()->all();
        return $this->createResponse($categories);
    }

}