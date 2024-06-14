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
        "product_name",
        "price",
    ];

    public function __construct(DatabaseHelper $db)
    {
        parent::__construct($db);
        $this->validation = new Validation($db);
    }

    public function validate($data): array
    {
        $errors = parent::validate($data);

        // Validate
        if (!isset($data['price']) || !is_numeric($data['price'])) {
            $errors['price'] = 'Price must be a valid number';
        }

        return $errors;
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'product_id');
    }

    public function category()
    {
        return new Category($this->db);
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

    public function update(array $data): bool
    {
        $id = $data[$this->primaryKey];
        unset($data[$this->primaryKey]);

        return $this->db->update($this->table, $data, $id);
    }

    public function save(array $data): int|bool
    {
        error_log('Saving product with data: ' . json_encode($data));
        return $this->db->create($this->table, $data);
    }
}
