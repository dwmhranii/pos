<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Transactions $model */

$this->title = 'Update Transactions : ' . $model->transaction_id;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->transaction_id, 'url' => ['view', 'transaction_id' => $model->transaction_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transactions-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
