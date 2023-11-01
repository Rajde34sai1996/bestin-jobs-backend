<?php

namespace common\models;
use yii\db\ActiveRecord;

use Yii;

/**
 * This is the model class for table "emergency_contact".
 *
 * @property int $id
 * @property int $user_id
 * @property string $relationship
 * @property int $phone
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 */
class EmergencyContact extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'emergency_contact';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'relationship', 'phone'], 'required'],
            [['user_id', 'phone'], 'integer'],
            [['relationship'], 'string', 'max' => 20],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }


    public function behaviors()
    {
        return [           
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                   ActiveRecord::EVENT_BEFORE_INSERT => ['created_at','updated_at'],
                   ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],

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
            'relationship' => 'Relationship',
            'phone' => 'Phone',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
