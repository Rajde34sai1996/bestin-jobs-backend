<?php

namespace common\components;

use common\models\BankDetails;
use common\models\Country;
use common\models\DeleteUsers;
use common\models\DeliveryPriceRange;
use common\models\EmailTemplate;
use common\models\Gourmet;
use common\models\GourmetType;
use common\models\GourmetTypeAssign;
use common\models\Images;
use common\models\Menu;
use common\models\MenuAssign;
use common\models\NotifyTemplate;
use common\models\Order;
use common\models\PhoneCode;
use common\models\ReviewRating;
use common\models\Ticket;
use common\models\User;
use common\models\UserocialConnect;
use common\models\Uuid;
use common\models\WeekDays;
use common\models\WeekTime;
use Exception;
use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;

class CommonUser extends \yii\base\Component
{

    public function makelogin($user, $type = false, $extra_data = array())
    {

        if ($user->status == User::STATUS_ACTIVE || $user->status == User::STATUS_INACTIVE) {
            if ($user->status == User::STATUS_INACTIVE) {
                $disable_user = User::findOne($user->id);
                $disable_user->status = User::STATUS_ACTIVE;
                if (!$disable_user->save(false)) {
                    return array('status' => false, 'message' => Yii::$app->general->error($disable_user->errors));
                }
            }
            $user->generateAccessTokenAfterUpdatingClientInfo(true);
            $data = [];
            $data['id'] = $user->id;
            $data['email'] = $user->email;
            $data['name'] = $user->username;
            $data['dob'] = $user->dob;
            $data['gender'] = $user->gender;
            $data['country_code'] = $user->country_code;
            $data['phone_number'] = $user->phone_number;
            $data['country'] = $user->country;
            $data['role'] = $user->role;
            $data['setting'] = $user->setting;
            $data['profile_pic'] = $user->profile_pic;
            $data['access_token'] = $user->access_token;
            $data['is_new'] = false;
            $data['step'] = 1;

            return [
                'status' => true,
                'message' => 'Login Successful',
                'data' => $data,
            ];
        } else if ($user->status == User::STATUS_PENDING) {
            // $SignupForm = new SignupForm();
            // $data = $this->sendOtp($user);
            // if($data['status']){
            return [
                'status' => true,
                'message' => 'Your account is not Active. Please Active.',
                // 'data'=> [ 'on_verification' => 1 ]
            ];
            // }else{
            //     return $data;
            // }
        } else if ($user->status == User::STATUS_DELETED) {
            return [
                'status' => false,
                'message' => 'Your account was deleted.'
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Status is invalid.'
            ];
        }
    }

}
