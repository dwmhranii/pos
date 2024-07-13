<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TransactionDetails $model */

$this->title = 'Create Transaction Details';
$this->params['breadcrumbs'][] = ['label' => 'Transaction Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
