<?php

namespace app\models;

use app\helpers\Errors;
use app\helpers\Functions;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property Users|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $phone;
    public $email;
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function attributeLabels()
    {
        return [
            'username'=>'username',
            'password'=>'password',
        ];
    }

    public function rules()
    {
        return [
            // username and password are both required
//            [['email_or_phone'],'match','pattern'=>Yii::$app->params['email_or_phone_pattern']],
            [['username', 'password'], 'required', 'message'=>Yii::t('app','require_error_message_default')],
//            [['username'], 'funkEmailOrPhone'],
//            [['phone'], 'match', 'pattern' => Yii::$app->params['phone_pattern'], 'message'=>Yii::t('app','INVALID_PHONE_NUMBER')],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function funkEmailOrPhone($attribute){
//        if(strpos($this->$attribute,"@")){
//            $this->email= $this->$attribute;
//            if(!filter_var($this->$attribute, FILTER_SANITIZE_EMAIL)){
//                $this->addError($attribute, Yii::t('app','warning_email_format'));
//            }
//        }
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app','incorrect_password'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return Users|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Users::findOne(['username'=>$this->username]);
        }

        return $this->_user;
    }
}
