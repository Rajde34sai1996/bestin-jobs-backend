<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "userotp".
 *
 * @property int $id
 * @property int $contry_code
 * @property string $phone_number
 * @property int $otp
 * @property string|null $auth_token
 * @property int|null $create_at
 * @property int|null $update_at
 */
class Userotp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userotp';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'exp_time',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'exp_time', // You can configure other attributes if needed
                ],
                'value' => function () {
                    return time(); // Format the timestamp as per your requirements
                },
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contry_code', 'phone_number', 'otp'], 'required'],
            [['contry_code', 'otp','exp_time'], 'integer'],
            [['phone_number'], 'string', 'max' => 20],
            [['auth_token'], 'string', 'max' => 255],
            [['exp_time'], 'safe'], 
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contry_code' => 'Contry Code',
            'phone_number' => 'Phone Number',
            'otp' => 'Otp',
            'auth_token' => 'Auth Token',
        ];
    }
}
