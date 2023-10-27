<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\db\Expression;
use common\models\Users;

class CheckExpire extends Component
{
    public function init()
    {
        if (!Yii::$app->user->isGuest && !Yii::$app->user->can('admin')) {
            $action     = $_SERVER['REQUEST_URI'];
            $data       = explode("/", $action);
            $ignore_actions = array(
                'logout',
            );
            $status = true;
            foreach ($ignore_actions as $action) {
                if (in_array($action, $data)) {
                    $status = false;
                }
            }

            $this->update_last_login();
        } else {
            return true;
        }
    }

    public function update_last_login()
    {
        $user_data  = Users::findOne(\Yii::$app->user->id);
        $current_ip = Yii::$app->request->getUserIP();

        $location_data  = Yii::$app->commonuser->get_location($current_ip);
        $time_zone      = ($location_data['status'] == 'success') ? $location_data['timezone'] : null;

        if ($user_data->signup_type == 'register' && $current_ip != $user_data->last_login_ip) {
            $user_data->last_login_ip   = $current_ip;
            $user_data->last_login_at   = new Expression('UNIX_TIMESTAMP()');
            $user_data->time_zone       = $time_zone;

            if (!$user_data->save(false)) {
                Yii::$app->general->createLogFile(\Yii::$app->general->error($user_data->errors));
            }

            Yii::$app->commonuser->get_ip_and_update_location($current_ip);

        }

        $time_zone  = $time_zone ? $time_zone : "UTC";
        date_default_timezone_set($time_zone);

        return;
    }
}
