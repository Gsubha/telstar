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
    <img src="/backend/web/themes/admin/img/404.png" width="100%">
</div>
