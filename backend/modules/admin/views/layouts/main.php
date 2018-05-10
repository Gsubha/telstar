<?php
/* @var $this View */
/* @var $content string */

use backend\assets\MyAppAsset;
use common\widgets\Alert;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

MyAppAsset::register($this);
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

<?php echo $content; ?>  
            <!-- content -->
        </div>
        <!-- /.right-side -->


<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
