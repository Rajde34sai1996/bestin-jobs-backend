<?php

namespace common\models;

use Yii;

use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "user_details".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $experience_level
 * @property int|null $working_time
 * @property string|null $work_preference
 * @property int|null $distance_level
 * @property int|null $whatsapp_number
 * @property int|null $uk_driving_license_number
 * @property bool|null $have_permission
 * @property int|null $dbs_number
 * @property int|null $skill_id
 * @property int|null $qualification_id
 * @property string|null $visa_category
 * @property string|null $visa_exp_date
 * @property int|null $national_insurance_number
 * @property string|null $experience_year
 * @property int|null $experience_month
 * @property string|null $current_orgnization
 * @property int $created_at
 * @property int $updated_at
 *
 * @property HealthcareQualification $qualification
 * @property Skills $skill
 * @property User $user
 */
class UserDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_details';
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['id', 'user_id', 'experience_level', 'working_time', 'distance_level', 'whatsapp_number', 'uk_driving_license_number', 'dbs_number', 'skill_id', 'qualification_id', 'national_insurance_number', 'experience_month', 'created_at', 'updated_at'], 'integer'],
            [['have_permission'], 'boolean'],
            [['visa_exp_date', 'experience_year'], 'safe'],
            [['work_preference', 'visa_category', 'current_orgnization'], 'string', 'max' => 50],
            [['qualification_id'], 'exist', 'skipOnError' => true, 'targetClass' => HealthcareQualification::class, 'targetAttribute' => ['qualification_id' => 'id']],
            [['skill_id'], 'exist', 'skipOnError' => true, 'targetClass' => Skills::class, 'targetAttribute' => ['skill_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'experience_level' => 'Experience Level',
            'working_time' => 'Working Time',
            'work_preference' => 'Work Preference',
            'distance_level' => 'Distance Level',
            'whatsapp_number' => 'Whatsapp Number',
            'uk_driving_license_number' => 'Uk Driving License Number',
            'have_permission' => 'Have Permission',
            'dbs_number' => 'Dbs Number',
            'skill_id' => 'Skill ID',
            'qualification_id' => 'Qualification ID',
            'visa_category' => 'Visa Category',
            'visa_exp_date' => 'Visa Exp Date',
            'national_insurance_number' => 'National Insurance Number',
            'experience_year' => 'Experience Year',
            'experience_month' => 'Experience Month',
            'current_orgnization' => 'Current Orgnization',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Qualification]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQualification()
    {
        return $this->hasOne(HealthcareQualification::class, ['id' => 'qualification_id']);
    }

    /**
     * Gets query for [[Skill]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkill()
    {
        return $this->hasOne(Skills::class, ['id' => 'skill_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
