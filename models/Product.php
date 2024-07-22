<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Mpdf\Mpdf;

/**
 * This is the model class for table "products".
 *
 * @property int $product_id
 * @property string $product_name
 * @property int|null $category_id
 * @property float $price
 * @property int|null $stock
 * @property string $created_at
 * @property string $updated_at
 * @property string|null $image
 * @property string|null $product_code
 * @property int|null $transaction_id
 *
 * @property Category $category
 * @property Transactiondetails[] $transactiondetails
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_name', 'price'], 'required'],
            [['category_id', 'stock'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['product_name', 'image', 'product_code'], 'string', 'max' => 100],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'category_id']],
            [['stock'], 'integer', 'min' => 0],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['product_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'product_name' => 'Product Name',
            'category_id' => 'Category ID',
            'price' => 'Price',
            'stock' => 'Stock',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'image' => 'Image',
            'product_code' => 'Product Code',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['category_id' => 'category_id']);
    }

    /**
     * Gets query for [[Transactiondetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactiondetails()
    {
        return $this->hasMany(Transactiondetails::class, ['product_id' => 'product_id']);
    }

    /**
     * Handles file upload.
     *
     * @return bool whether the upload was successful
     */
    public function uploadImage()
    {
        if ($this->validate(['imageFile'])) {
            $path = 'images/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
            if ($this->imageFile->saveAs($path)) {
                $this->image = $path;
                return true;
            }
        }
        return false;
    }

    /**
     * Reduces the stock by a given quantity.
     *
     * @param int $quantity
     * @return bool
     */
    public function reduceStock($quantity)
    {
        $this->stock -= $quantity;
        return $this->save(false); // Save tanpa validasi
    }

    /**
     * Fetches product details via AJAX.
     * 
     * @return array
     */
    public function actionGetProductDetails()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $product_id = Yii::$app->request->post('id');
        $product = self::findOne($product_id);

        if ($product) {
            return [
                'product_id' => $product->product_id,
                'product_name' => $product->product_name,
                'price' => $product->price,
            ];
        }

        return [];
    }

    /**
     * Generates a new product code based on the category ID.
     *
     * @param int $category_id
     * @return string
     */
    public function generateProductCode($category_id)
    {
        // Ambil kode kategori
        $category = Category::findOne($category_id);
        if ($category) {
            $categoryCode = $category->kode_kategori;
        } else {
            return '';
        }
        
        // Cari produk terakhir untuk kategori ini
        $lastProduct = self::find()
            ->where(['category_id' => $category_id])
            ->orderBy(['product_id' => SORT_DESC])
            ->one();

        // Tentukan kode produk baru
        if ($lastProduct && $lastProduct->product_code) {
            $lastCode = (int) substr($lastProduct->product_code, -5);
            $newCode = $lastCode + 1;
        } else {
            $newCode = 11111; // mulai dari P11111
        }

        return $categoryCode . sprintf("%05d", $newCode);
    }

    /**
     * Override save method to generate product code.
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord && empty($this->product_code)) {
                $this->product_code = $this->generateProductCode($this->category_id);
            }
            return true;
        } else {
            return false;
        }
    }


    public function getBarcode()
    {
        if (empty($this->product_code)) {
            return null;
        }

        $generator = new BarcodeGeneratorPNG();
        return $generator->getBarcode($this->product_code, $generator::TYPE_CODE_128);
    }

    // Metode untuk mencetak barcode
    public function printBarcode()
    {
        $barcode = $this->getBarcode();
        if (empty($barcode)) {
            throw new \Exception('Product code is empty, cannot generate barcode.');
        }

        $pdf = new Mpdf();
        $barcodeBase64 = base64_encode($barcode);

        $html = '
            <p>' . $this->product_name . '</p>
            <img src="data:image/png;base64,' . $barcodeBase64 . '" /> 
        ';

        $pdf->WriteHTML($html);
        return $pdf->Output('Product_Barcode.pdf', \Mpdf\Output\Destination::INLINE);
    }

    public static function primaryKey()
    {
        return ['product_id'];
    }

}
