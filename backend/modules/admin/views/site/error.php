<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<style type="text/css">.content-header{display:none;}</style>
<div class="site-error">
    <h1><center><?= nl2br(Html::encode($message)) ?></center></h1>
    <img src="/backend/web/themes/admin/img/404.png" class="user-image" alt="User Image" width="100%">
    
<!--    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        The above error occurred while the Web server was processing your request.
    </p>
    <p>
        Please contact us if you think this is a server error. Thank you.
    </p>-->

</div>
