<?php

use app\models\TransactionDetails;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Transaction Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-details-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Transaction Details', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'detail_id',
            'transaction_id',
            'product_id',
            'quantity',
            'price',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, TransactionDetails $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'detail_id' => $model->detail_id]);
                 }
            ],
        ],
    ]); ?>


</div>
