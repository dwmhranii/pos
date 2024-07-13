<?php
use yii\helpers\Html;

/* @var $models app\models\Transactions[] */

?>
<h1>Transaction Report</h1>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Transaction ID</th>
            <th>User ID</th>
            <th>Total</th>
            <th>Transaction Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($models as $model): ?>
        <tr>
            <td><?= Html::encode($model->transaction_id) ?></td>
            <td><?= Html::encode($model->user_id) ?></td>
            <td><?= Html::encode($model->total) ?></td>
            <td><?= Html::encode($model->transaction_date) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
