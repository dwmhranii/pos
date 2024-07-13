<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Transactions $model */

$this->title = $model->transaction_code;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transactions-view">

    <p>
        <?= Html::a('<i class="fas fa-arrow-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Delete', ['delete', 'transaction_id' => $model->transaction_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'transaction_code',
            'user_id',
            'total',
            'transaction_date',
            'amount_paid',
            'change_returned',
        ],
    ]) ?>

    <h3>Transaction Details</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model->transactionDetails as $detail): ?>
                <tr>
                    <td><?= $detail->product_id ?></td>
                    <td><?= $detail->quantity ?></td>
                    <td><?= $detail->price ?></td>
                    <td><?= $detail->total_price ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
