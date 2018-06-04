<?php

use backend\assets\AppAssetAdmin;
use common\widgets\Alert;

AppAssetAdmin::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Login</title>    
        <?php $this->head() ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">      
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    </head>
    <body class="hold-transition login-page">
        <?php $this->beginBody() ?>
        <?= Alert::widget() ?>
        <?php echo $content ?>       
        <?php $this->endBody() ?>
    </body>
</html>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
<?php $this->endPage() ?>