<?php

namespace common\components;

use Yii;

class General extends \yii\base\Component {


    public function error($errors)
    {
        $error = [];
        foreach ($errors as $key => $value) {
            $error[] = current($value);
        }
        return implode(" , ", $error);
    }
   
}