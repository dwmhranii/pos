<?php
use yii\grid\GridView;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'transaction_id',
        'user_id',
        'total',
        'transaction_date',
    ],
]);
