<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "work_preference".
 *
 * @property int $id
 * @property string $selected_preference
 * @property int $user_id
 *
 * @property User $user
 */
class WorkPreference extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work_preference';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['selected_preference', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            [['selected_preference'], 'string', 'max' => 20],
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
            'selected_preference' => 'Selected Preference',
            'user_id' => 'User ID',
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
