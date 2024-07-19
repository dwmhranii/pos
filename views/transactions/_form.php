<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Product;

/** @var yii\web\View $this */
/** @var app\models\Transactions $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="transactions-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'transaction_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'user_id')->textInput(['readonly' => true, 'value' => Yii::$app->user->identity->id]) ?>

    <?= $form->field($model, 'transaction_date')->textInput(['readonly' => true, 'id' => 'transaction-date']) ?>

    <?= $form->field($model, 'transaction_code')->textInput(['readonly' => true]) ?>

    <div class="form-group">
        <label for="product-list">Products</label>
        <input type="text" id="product-search" class="form-control" placeholder="Cari produk..">
        <table class="table table-bordered" id="product-list">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th><button type="button" class="btn btn-success add-product">Add Product</button></th>
                </tr>
            </thead>
            <tbody>
                <!-- Product rows will be appended here by JavaScript -->
            </tbody>
        </table>
    </div>

    <?= $form->field($model, 'total')->textInput(['readonly' => true, 'id' => 'transactions-total']) ?>

    <?= $form->field($model, 'amount_paid')->textInput(['id' => 'amount-paid']) ?>

    <?= $form->field($model, 'change_returned')->textInput(['readonly' => true, 'id' => 'change-returned']) ?>

    <!-- Hidden input for storing transaction details as JSON -->
    <?= Html::hiddenInput('TransactionDetailsJson', '', ['id' => 'transaction-details-json']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// Prepare the product options in PHP and encode them to JSON
$productOptions = ArrayHelper::map(Product::find()->all(), 'product_id', 'product_name');
$options = "";
foreach ($productOptions as $product_id => $name) {
    $options .= "<option value='{$product_id}'>{$name}</option>";
}

// Register the JavaScript for dynamic form
$this->registerJs(<<<JS
function updateRowTotal(row) {
    var quantity = row.find('.quantity').val();
    var price = row.find('.price').val();
    var rowTotal = quantity * price;
    row.find('.row-total').val(rowTotal.toFixed(2));
    updateTotal();
}

function updateTotal() {
    var total = 0;
    $('#product-list tbody .row-total').each(function () {
        total += parseFloat($(this).val());
    });
    $('#transactions-total').val(total.toFixed(2));
    updateChangeReturned();
}

function updateChangeReturned() {
    var total = parseFloat($('#transactions-total').val());
    var amountPaid = parseFloat($('#amount-paid').val());
    if (!isNaN(total) && !isNaN(amountPaid)) {
        var changeReturned = amountPaid - total;
        $('#change-returned').val(changeReturned.toFixed(2));
    }
}

$(document).on('click', '.add-product', function () {
    var productRow = `<tr>
        <td>
            <select class="form-control product-id">
                $options
            </select>
        </td>
        <td>
            <input type="number" class="form-control quantity" value="1" min="1" />
        </td>
        <td>
            <input type="number" class="form-control price" value="0" step="0.01" readonly />
        </td>
        <td>
            <input type="number" class="form-control row-total" value="0" step="0.01" readonly />
        </td>
        <td>
            <button type="button" class="btn btn-danger remove-product">Remove</button>
        </td>
    </tr>`;
    $('#product-list tbody').append(productRow);
    updateTotal();
});

$(document).on('click', '.remove-product', function () {
    $(this).closest('tr').remove();
    updateTotal();
});

$(document).on('change', '.product-id', function () {
    var productId = $(this).val();
    var priceInput = $(this).closest('tr').find('.price');
    $.get('/transactions/get-product-price', { id: productId }, function (data) {
        priceInput.val(data.price);
        updateRowTotal(priceInput.closest('tr'));
    });
});

$(document).on('change', '.quantity', function () {
    updateRowTotal($(this).closest('tr'));
});

$(document).on('change', '#amount-paid', function () {
    updateChangeReturned();
});

function updateDateTime() {
    var now = new Date();
    var formattedDate = now.getFullYear() + '-' +
        ('0' + (now.getMonth() + 1)).slice(-2) + '-' +
        ('0' + now.getDate()).slice(-2) + ' ' +
        ('0' + now.getHours()).slice(-2) + ':' +
        ('0' + now.getMinutes()).slice(-2) + ':' +
        ('0' + now.getSeconds()).slice(-2);
    document.getElementById('transaction-date').value = formattedDate;
}

// Update date time every second
setInterval(updateDateTime, 1000);

// Set initial value
updateDateTime();

// Before submitting the form, collect transaction details
$('form').on('beforeSubmit', function() {
    var details = [];
    $('#product-list tbody tr').each(function() {
        var row = $(this);
        details.push({
            product_id: row.find('.product-id').val(),
            quantity: row.find('.quantity').val(),
            price: row.find('.price').val()
        });
    });
    $('#transaction-details-json').val(JSON.stringify(details));
    return true;
});

// Search function for products
$(document).on('keyup', '#product-search', function() {
    var value = $(this).val().toLowerCase();
    $("#product-list tbody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});

JS
);
?>
