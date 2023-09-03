<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "friends".
 *
 * @property int $id
 * @property int|null $user_l
 * @property int|null $user_r
 * @property int|null $status
 * @property string|null $create_at
 * @property string|null $update_at
 */
class Friends extends \yii\db\ActiveRecord
{
    const STATUS_REQUEST = 1;
    const STATUS_OK = 2;
    const STATUS_DELETED = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'friends';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_l', 'user_r', 'status'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_l' => Yii::t('app', 'User L'),
            'user_r' => Yii::t('app', 'User R'),
            'status' => Yii::t('app', 'Status'),
            'create_at' => Yii::t('app', 'Create At'),
            'update_at' => Yii::t('app', 'Update At'),
        ];
    }

    public static function getFriends()
    {
        $data = Friends::find()->
        select('friends.*')->
        addSelect("(CASE WHEN us1.id = " . Yii::$app->user->id . " THEN us2.username ELSE us1.username END) as 'friend_username' ")->
        addSelect("(CASE WHEN us1.id = " . Yii::$app->user->id . " THEN us2.email ELSE us1.email END) as 'friend_email' ")->
        addSelect("(CASE WHEN us1.id = " . Yii::$app->user->id . " THEN us2.fname ELSE us1.fname END) as 'friend_fname' ")->
        addSelect("(CASE WHEN us1.id = " . Yii::$app->user->id . " THEN us2.fname ELSE us1.lname END) as 'friend_lname' ")
            ->leftJoin('users AS us1', 'us1.id=friends.user_r')
            ->leftJoin('users AS us2', 'us2.id=friends.user_l')
            ->andWhere(['friends.status' => Friends::STATUS_OK])
            ->andWhere(['or',
                ['user_l' => Yii::$app->user->id],
                ['user_r' => Yii::$app->user->id]
            ])->asArray()->all();


        return $data;

    }
    public static function getRequest()
    {
        $data = Friends::find()->
        select('friends.*,us1.username as friend_username,us1.email as friend_email,us1.fname as friend_fname,us1.lname as friend_lname')

            ->leftJoin('users AS us1', 'us1.id=friends.user_r')
            ->andWhere(['friends.status' => Friends::STATUS_REQUEST])
            ->andWhere(['user_l' => Yii::$app->user->id])
            ->asArray()->all();
//                ['user_r' => Yii::$app->user->id]
        return $data;
    }
    public static function getInvent()
    {
        $data = Friends::find()->
        select('friends.*,us1.username as friend_username,us1.email as friend_email,us1.fname as friend_fname,us1.lname as friend_lname')

            ->leftJoin('users AS us1', 'us1.id=friends.user_l')
            ->andWhere(['friends.status' => Friends::STATUS_REQUEST])
            ->andWhere(['user_r' => Yii::$app->user->id])
            ->asArray()->all();
        return $data;
    }

}
