<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

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
                'value' => new Expression('NOW()'),
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
            [['contry_code', 'otp'], 'integer'],
            [['created_at', 'updated_at'],'datetime'],
            [['phone_number'], 'string', 'max' => 20],
            [['auth_token'], 'string', 'max' => 255],
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
            'created_at' => 'Create At',
            'updated_at' => 'Update At',
        ];
    }
}
