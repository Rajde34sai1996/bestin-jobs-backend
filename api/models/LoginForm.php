<?php

namespace app\models;

use common\models\Userotp;
use common\models\User;
use Yii;
use yii\base\Model;
use yii\validators\RequiredValidator;

use lajax\translatemanager\helpers\Language as Lx;

/**
 * LoginForm is the model behind the login form.
 *
 * @property Users|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $phone_number;
    public $contry_code;
    public $role;
    public $otp;
    public $is_new = true;
    private $_user = false;

    /** @var Users */

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['phone_number', 'contry_code', 'otp', 'role'], 'required'],
            [['phone_number','contry_code','otp', 'role'], 'safe'],

        ];
    }


    public function Check_OTP($contry_code,$phone_number,$otp,$role = 'user')
    {

        try {
            $VerifyCode =  Userotp::findOne(['contry_code' => $contry_code,'phone_number' => $phone_number, 'otp' => $otp]);
            if (!empty($VerifyCode)) {
                return array('status' => true, 'message' => "otp verify");
            } 
            return array('status' => false, 'message' => "Invalid OTP");
        } catch (\Exception $e) {
            Yii::$app->general->createLogFile($e);
            return array('status' => false, 'message' => $e->getMessage());
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        $isCheck  = $this->Check_OTP($this->contry_code,$this->phone_number,$this->otp);  
        if($isCheck && $isCheck['status']){
            $findUser = User::findByPhoneNumber($this->phone_number,$this->contry_code,$this->role);
            if($findUser){
                if($this->validate()){
                    $this->_user = $findUser;
                    return Yii::$app->user->login($findUser,1);
    
                }else {
                    $this->addError('login', "that you've entered doesn't match any account.");
                }
            }
            $this->is_new = false;
            return  $isCheck;

        }
    }

    /**
     * Return Users object
     *
     * @return Users
     */
    public function getUser()
    {
        return $this->_user;
    }
}
