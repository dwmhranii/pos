<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TransactionDetails $model */

$this->title = 'Update Transaction Details: ' . $model->detail_id;
$this->params['breadcrumbs'][] = ['label' => 'Transaction Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->detail_id, 'url' => ['view', 'detail_id' => $model->detail_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transaction-details-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
