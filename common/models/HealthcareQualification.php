<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Healthcare_Qualification".
 *
 * @property int $id
 * @property string $qualification_name
 * @property string $status
 * @property int $created_at
 * @property int $updated_at
 */
class HealthcareQualification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Healthcare_Qualification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['qualification_name', 'status'], 'required'],
            [['status'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['qualification_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'qualification_name' => 'Qualification Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
