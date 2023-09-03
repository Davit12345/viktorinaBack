<?php


namespace app\controllers\api;


use app\models\Friends;
use app\models\Users;
use Yii;

class FriendController extends ApiBaseController
{
    public function actionGetFriends()
    {
        $type = \Yii::$app->request->post('type');

        if ($type == 'request') {
            $data = Friends::getRequest();
        } else if ($type == 'invent') {
            $data = Friends::getInvent();

        } else {
            $data = Friends::getFriends();

        }


        return $this->createResponse(['status' => 'ok', 'data' => $data]);
    }

    public function actionSearchFriend()
    {

        $search = \Yii::$app->request->post('search');

        $user = Users::find()->andWhere(['or',
            ['=', 'username', $search],
            ['=', 'email', $search]
        ])->one();

        $friend = null;

        if ($user && $user['id'] != Yii::$app->user->id) {
            $friend = Friends::find()->
            andWhere(['or',
                ['user_l' => $user->id, 'user_r' => Yii::$app->user->id],
                ['user_r' => $user->id, 'user_l' => Yii::$app->user->id],
            ])->andWhere(['!=', 'status', Friends::STATUS_DELETED])
                ->one();
        } else {
            $user = null;
        }

        return $this->createResponse(['status' => 'ok', 'user' => $user, 'friend' => $friend]);


    }

    public function actionAddRequest()
    {
        $friend_id = \Yii::$app->request->post('friend_id');
        $friend = new Friends();

        $friend->status=Friends::STATUS_REQUEST;
        $friend->user_l=Yii::$app->user->id;
        $friend->user_r=$friend_id;
        $friend->save();
        return $this->createResponse(['status' => 'ok']);

    }
    public function actionConfirmRequest()
    {
        $id = \Yii::$app->request->post('id');
        $friend = Friends::findOne(['id'=>$id]);
        $friend->status=Friends::STATUS_OK;
        $friend->save();
        return $this->createResponse(['status' => 'ok']);

    }

}