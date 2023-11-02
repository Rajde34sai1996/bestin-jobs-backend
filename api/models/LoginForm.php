<?php

namespace app\models;

use common\components\General;
use common\models\Userotp;
use common\models\User;
use Yii;
use yii\base\Model;
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
            [['phone_number', 'contry_code', 'otp', 'role'], 'safe'],

        ];
    }


    public function Check_OTP($contry_code, $phone_number, $otp, $role = 'user')
    {
        try {
            $VerifyCode = Userotp::findOne(['contry_code' => $contry_code, 'phone_number' => $phone_number, 'otp' => $otp]);
            $obj = new General();

            if (!$VerifyCode) {
                return ['success' => false, 'message' => 'Invalid OTP. Please try again.'];
            }
            $minutesDifference = $obj->getMinute($VerifyCode->exp_time);


            // Convert the minutes difference to relative time using Yii2 formatter
            // $otpTimestamp = strtotime($VerifyCode->exp_time);
            if ($minutesDifference >= 1) { // Check if the difference is greater than or equal to 60 seconds
                return ['success' => false, 'message' => 'OTP expired. Please request a new OTP.', 'data' => $minutesDifference];
            }

            return ['success' => true, 'message' => 'OTP verified successfully.'];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'An error occurred while verifying OTP. Please try again later.'];
        }
    }


    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        $isCheck = $this->Check_OTP($this->contry_code, $this->phone_number, $this->otp);

        if ($isCheck['success']) {
            $findUser = User::findByPhoneNumber($this->phone_number, $this->contry_code, $this->role);
            if ($findUser) {
                if ($this->validate()) {
                    $this->_user = $findUser;
                    return Yii::$app->user->login($findUser, 0);
                } else {
                    $this->addError('login', "The phone number you've entered doesn't match any account.");
                }
            } else {
                $this->is_new = false;
                return $isCheck;
            }
        } else {
            $this->addError('login', $isCheck['message']);
        }

        return false;
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
