<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\common\models\Deductions */

$this->title = 'Create Tech';
$this->params['breadcrumbs'][] = ['label' => 'Tech', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
    <?=
    $this->render('_form', [
        'model' => $model
    ])
    ?>

</div>
