<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Helper\DatabaseHelper;
use App\Helper\Validation;

class Inventory extends BaseModel
{
    protected $table = 'inventory';
    protected $primaryKey = 'id';
    protected $validation;
    protected $requiredFields = [
        'product_id'
        // 'reorder_point',
        // 'safety_stock'
    ];

    public function __construct(DatabaseHelper $db)
    {
        parent::__construct($db);
        $this->validation = new Validation($db);
    }

    public function validate($data): array
    {
        $errors = parent::validate($data);

        // Validate product_id
        if (!isset($data['product_id']) || !is_numeric($data['product_id'])) {
            $errors['product_id'] = 'Product ID must be a valid number';
        }

        return $errors;
    }

    public function update(array $data): bool
    {
        $id = $data[$this->primaryKey];
        unset($data[$this->primaryKey]);

        return $this->db->update($this->table, $data, $id);
    }

    public function delete($id): bool
    {
        return $this->db->hard_delete($this->table, $id);
    }

    public function save(array $data): int|bool
    {
        error_log('Saving inventory with data: ' . json_encode($data));
        return $this->db->create($this->table, $data);
    }
}
