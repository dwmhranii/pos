<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->product_name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <p>
        <?= Html::a('<i class="fas fa-arrow-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Update', ['update', 'product_id' => $model->product_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'product_id' => $model->product_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Print Barcode', ['print-barcode', 'product_id' => $model->product_id], ['class' => 'btn btn-info', 'target' => '_blank']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'product_id',
            'product_name',
            'product_code',
            [
                'attribute' => 'barcode',
                'format' => 'raw',
                'value' => function($model) {
                    $barcode = $model->getBarcode();
                    return $barcode ? Html::img('data:image/png;base64,' . base64_encode($barcode)) : 'Barcode not available';
                },
            ],
            'category_id',
            'price',
            'stock',
            'created_at',
            'updated_at',
            [
                'attribute' => 'image',
                'value' => function ($model) {
                    return $model->image ? Html::img(Yii::getAlias('@web') . '/' . $model->image, ['width' => '200px']) : null;
                },
                'format' => 'raw',
            ],
        ],
    ]) ?>

</div>
