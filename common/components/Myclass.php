<?php

namespace common\components;

use Yii;
use yii\base\Component;

class Myclass extends Component {

    public static function pageOptions() {
        return [10 => 10, 20 => 20, 50 => 50, 150 => 150, 200 => 200, 250 => 250];
    }
}
