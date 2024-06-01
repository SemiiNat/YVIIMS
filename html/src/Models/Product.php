<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Helper\DatabaseHelper;
use App\Helper\Validation;

class Product extends BaseModel
{

    protected $table = 'product';
    protected $primaryKey = 'id';
    protected $validation;
    protected $requiredFields = [
        'category_id',
        "sku",
        "product_name",
    ];

    public function __construct(DatabaseHelper $db)
    {
        parent::__construct($db);
        $this->validation = new Validation($db);
    }

    public function validate($data): array
    {
        $errors = parent::validate($data);

        if (!$this->validation->isUnique($this->table, 'sku', $data['sku'])) {
            $errors['sku'] = 'SKU already exists';
        }

        return $errors;
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'product_id',);
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function attachSuppliers($productId, array $supplierIds)
    {
        foreach ($supplierIds as $supplierId) {
            $this->db->create('supplierProduct', [
                'product_id' => $productId,
                'supplier_id' => $supplierId
            ]);
        }
    }
}
