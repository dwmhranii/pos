<?php
use yii\helpers\Html;
// use Yii;
?>

<div class="detail">
    <div class="header" style="text-align: center;">
        <p style="font-weight: bold;">TOKO LANCAR BAROKAH</p>
        <span>Jl. Rokan No. 13B, Pandean, Madiun<br>No. Telp 08xxxxxxxxxx</span>
    </div>

    <div class="info" style="margin-top: 10px;">
        <p>Kode Transaksi: <?= Html::encode($transaction->transaction_code) ?></p>
        <p>Kasir: <?= Html::encode(Yii::$app->user->identity->username) ?></p>
        <p>Tgl: <?= Yii::$app->formatter->asDatetime($transaction->transaction_date, 'php:d-m-Y H:i:s') ?></p>
    </div>

    <div class="items" style="margin-top: 10px; border-top: 1px dashed black; border-bottom: 1px dashed black; padding: 10px 0;">
        <?php foreach ($transaction->transactionDetails as $detail): ?>
            <p>
                <?= Html::encode($detail->product->product_name) ?><br>
                <?= Html::encode($detail->quantity) ?> x <?= Yii::$app->formatter->asCurrency($detail->price) ?>
            </p>
        <?php endforeach; ?>
    </div>
</div>
