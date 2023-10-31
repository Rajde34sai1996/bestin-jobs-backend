<?php

namespace app\models;

use common\models\UserDetails;
use common\models\WorkPreference;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property UserDetailsForm|null $user This property is read-only.
 *
 */
class UserDetailsForm extends Model
{
    public $id;
    public $user_id;
    public $experience_level;
    public $working_time;
    public $work_preference;
    public $distance_level;
    public $whatsapp_number;
    public $uk_driving_license_number;
    public $have_permission;
    public $dbs_number;
    public $skill_id;
    public $qualification_id;
    public $visa_category;
    public $visa_exp_date;
    public $national_insurance_number;
    public $experience_year;
    public $experience_month;
    public $current_orgnization;
    public $created_at;
    public $updated_at;

    /** @var UsersDetails */

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['user_id'], 'required'],
            [['id', 'user_id', 'experience_level', 'working_time', 'distance_level', 'whatsapp_number', 'uk_driving_license_number', 'dbs_number', 'skill_id', 'qualification_id', 'national_insurance_number', 'experience_month', 'created_at', 'updated_at'], 'integer'],
            [['work_preference'], 'safe'],


        ];
    }
    public function Add_Profile()
    {

        if ($this->validate()) {
            $userDetails = UserDetails::findOne(['user_id' => $this->user_id]);
            $WorkPreff = WorkPreference::findOne(['user_id' => $this->user_id, 'selected_preference' => $this->work_preference]);
            if (!$userDetails) {
                // If no record found, create a new one
                $userDetails = new UserDetails();
                $userDetails->user_id = $this->user_id;
            }
            if (!$WorkPreff) {
                $WorkPreff = new WorkPreference();
                $WorkPreff->user_id = $this->user_id;
                $WorkPreff->selected_preference = $this->work_preference;
            }
            if ($this->work_preference) {
                $WorkPreff->selected_preference = $this->work_preference;
            }
            $userDetails->experience_level = $this->experience_level;
            $userDetails->working_time = $this->working_time;
            $userDetails->distance_level = $this->distance_level;
            $userDetails->whatsapp_number = $this->whatsapp_number;
            $userDetails->uk_driving_license_number = $this->uk_driving_license_number;
            $userDetails->dbs_number = $this->dbs_number;
            $userDetails->skill_id = $this->skill_id;
            $userDetails->qualification_id = $this->qualification_id;
            $userDetails->national_insurance_number = $this->national_insurance_number;
            $userDetails->experience_month = $this->experience_month;

            if ($userDetails->save() && $WorkPreff->save()) {
                return true; // Successfully inserted
            } else {
                Yii::error('Failed to insert user details: ' . print_r($userDetails->errors, true));
                return false; // Failed to insert
            }
        } else {
            Yii::error('Validation failed: ' . print_r($this->errors, true));
            return false; // Validation failed
        }


    }
    public function findAllByID($id)
    {
        $userData = UserDetails::find()->where(['user_id' => $id])->asArray()->one();
        $workPreferences = WorkPreference::find()->where(['user_id' => $id])->asArray()->all();

        if ($userData !== null) {
            $formattedData = [
                'user_details' => $userData,
                'work_preferences' => $workPreferences,
            ];

            return $formattedData;
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
