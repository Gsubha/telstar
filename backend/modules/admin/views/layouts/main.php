<?php
/* @var $this View */
/* @var $content string */

use backend\assets\AppAssetAdmin;
use common\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Breadcrumbs;

AppAssetAdmin::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title>TelStar</title>
        <?php $this->head() ?>
    </head>
    <?php
    if(Yii::$app->user->id)
    {
    ?>
    <body class=" skin-green">
        <?php $this->beginBody() ?>
        <?php $this->beginContent('@app/modules/admin/views/layouts/header.php'); ?>
        <?php $this->endContent(); ?>
        <?php $this->beginContent('@app/modules/admin/views/layouts/sidebar.php'); ?>
        <?php $this->endContent(); ?>
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    <?= Html::encode($this->title) ?>
                </h1>
                <?php
                echo Breadcrumbs::widget([
                    'homeLink' => [
                        'label' => Yii::t('yii', 'Dashboard'),
                        'url' => Yii::$app->homeUrl,
                    ],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]);
                ?>
            </section>
            <div class="row">
                <div class="col-md-12">
                <?= Alert::widget() ?>
            </div></div>
            <?php echo $content; ?>  
            <!-- content -->
        </div>
        <!-- /.right-side -->


        <?php $this->endBody() ?>
    </body>
   <?php } else {
     Yii::$app->user->logout();
     return Yii::$app->response->redirect(Yii::$app->homeUrl);
   } ?>
</html>
<?php $this->endPage() ?>
