<?php
/**
 * Created by PhpStorm.
 * User: Aram
 * Date: 3/20/2018
 * Time: 8:59 PM
 */

namespace app\helpers;


use Yii;
use yii\web\HttpException;

class Errors
{

    const INVALID_PHONE_NUMBER = 'invalid_phone_number';
    const  BAD_REQUEST = 400;
    const  UNAUTHORIZED  = 401;
    const  FORBIDDEN_ERROR = 403;
    const  NOT_FOUND = 404;

    const  INTERNAL_SERVER_ERROR = 500;


    public static function getMessage($key){

        /*$messages=[
        $messages=[

        $messages=[
            'reg_not_found'=>'Առկա է սխալ։',
            'user_not_found'=>'Մուտքանունը կամ գաղտնաբառը սխալ է։',
            'not_found'=>'Մուտքագրել եք սխալ տվալ',
            'phone_not_found'=>'Մուտքագրվաց հեռախոսահամարը սխալ է',
            'phone_not_found'=>'not found of this phone'
            'phone_not_found'=>'not found of this phone',
			'arca_error'=> "error form arca side",
			"balance_phase_error"=>Yii::t('app', 'balance_phase_error')

        ];*/

        return \Yii::t('mobile',$key);
    }
    public static function wrongError(){
        throw new HttpException(Errors::BAD_REQUEST, Functions::t('tr_something_went_wrong'));
    }
    public static function ex($message, $status=Errors::BAD_REQUEST){
        throw new HttpException($status, Functions::t($message));
    }
}