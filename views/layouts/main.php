<?php

use hail812\adminlte3\assets;
use hail812\adminlte3\assets\AdminLteAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

AdminLteAsset::register($this);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>

<div class="wrapper">
    <?= $this->render('header.php') ?>
    <?= $this->render('left.php') ?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1><?= $this->title ?></h1>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </section>
        <section class="content">
            <?= $content ?>
        </section>
    </div>
    <?= $this->render('footer.php') ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
