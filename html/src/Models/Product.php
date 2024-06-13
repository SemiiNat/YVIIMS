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
        "reorder_point",
        "economic_order_quantity",
        "critical_level",
        "manufacturing_date"
    ];

    public function __construct(DatabaseHelper $db)
    {
        parent::__construct($db);
        $this->validation = new Validation($db);
    }

    public function validate($data): array
    {
        $errors = parent::validate($data);

        // Validate SKU
        if (isset($data['sku']) && !$this->validation->isUnique($this->table, 'sku', $data['sku'])) {
            $errors['sku'] = 'SKU already exists';
        }

        // Validate price
        if (!isset($data['price']) || !is_numeric($data['price'])) {
            $errors['price'] = 'Price must be a valid number';
        }

        // Validate reorder point
        if (!isset($data['reorder_point']) || !is_numeric($data['reorder_point'])) {
            $errors['reorder_point'] = 'Reorder point is required';
        }

        // Validate economic order quantity
        if (!isset($data['economic_order_quantity']) || !is_numeric($data['economic_order_quantity'])) {
            $errors['economic_order_quantity'] = 'Economic order quantity is required';
        }

        // Validate critical level
        if (!isset($data['critical_level']) || !is_numeric($data['critical_level'])) {
            $errors['critical_level'] = 'Critical level is required';
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

    public function getLatestProductBySKU(string $skuPrefix): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE sku LIKE ? ORDER BY id DESC LIMIT 1";
        $params = ["{$skuPrefix}%"];
        return $this->db->getOne($sql, $params);
    }

    public function save(array $data): int|bool
    {
        error_log('Saving product with data: ' . json_encode($data));
        return $this->db->create($this->table, $data);
    }
}
