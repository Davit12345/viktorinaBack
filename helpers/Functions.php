<?php

namespace app\helpers;

use Yii;

class Functions
{

    public static function generateTokenMD5($token)
    {
        return md5('super' . date('YmdHis') . $token . 'api');
    }
    public static function clearPhone($phone)
    {
        return substr($phone,-8);
    }


    public static function t($text)
    {
        return Yii::t('app', $text);
    }


    public static  function  sqlDateFormat($date){
        $d=date_create($date);
        $r= date_format($d,"Y-m-d H:i:s");
        return $r;
    }
}
