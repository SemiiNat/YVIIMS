<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Helper\DatabaseHelper;
use App\Helper\Validation;

class Batch extends BaseModel
{
    protected $table = 'batch';
    protected $primaryKey = 'id';
    protected $validation;
    protected $requiredFields = [
        'product_id',
        'sku',
        'manufacturing_date',
        'expiration_date',
        'quantity'
    ];

    public function __construct(DatabaseHelper $db)
    {
        parent::__construct($db);
        $this->validation = new Validation($db);
    }

    public function validate($data): array
    {
        $errors = parent::validate($data);

        // Validate quantity
        if (!isset($data['quantity']) || !is_numeric($data['quantity'])) {
            $errors['quantity'] = 'Quantity must be a valid number';
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
        error_log('Saving batch with data: ' . json_encode($data));
        return $this->db->create($this->table, $data);
    }
}
