<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $type_id
 * @property string|null $name
 * @property string|null $created_at
 */
class Files extends \yii\db\ActiveRecord
{
    const UPLOAD_FOLDER = 'all';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'type_id'], 'integer'],
            [['created_at'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'type_id' => Yii::t('app', 'Type ID'),
            'name' => Yii::t('app', 'Name'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }


    public static function getUploadFolder()
    {
        return Yii::$app->params['upload_path'] . DIRECTORY_SEPARATOR . self::UPLOAD_FOLDER . DIRECTORY_SEPARATOR;
    }
    public function getIconUrl()
    {
        return $this->name ? '\\' . self::getUploadFolder() . $this->name : null;
    }

    public static function getFullUrl($url)
    {
        return $url ? '' . self::getUploadFolder() . $url : null;
    }
    public static function getFullUrlUnName($url)
    {
        return $url ? '' . self::getUploadFolder() . $url : null;
    }

}
