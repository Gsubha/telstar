<?php

namespace common\components;

use Yii;
use yii\base\Component;

class Myclass extends Component {

    public static function pageOptions() {
        return [10 => 10, 20 => 20, 50 => 50, 150 => 150, 200 => 200, 250 => 250];
    }
    
    public static function validateDate($date='')
    {
        if(!empty($date))
        {
            $exp=explode("/",$date);
            $month=@$exp[0];
            $day=@$exp[1];
            $year=@$exp[2];
            return @checkdate($month,$day,$year);
        }
        else{
            return false;
        }
        
    }
}
