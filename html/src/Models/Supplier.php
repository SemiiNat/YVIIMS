<?php

namespace App\Models;

use App\Helper\DatabaseHelper;
use App\Helper\Validation;

class Supplier extends BaseModel
{
    protected $table = 'supplier';
    protected $primaryKey = 'id';
    protected $requiredFields = [
        "supplier_name",
        "phone_number",
        "email",
    ];
    protected $validation;

    public function __construct(DatabaseHelper $db)
    {
        parent::__construct($db);
        $this->validation = new Validation($db);
    }

    function validate($data): array
    {
        $errors = parent::validate($data);

        if (!$this->validation->isUnique($this->table, 'supplier_name', $data['supplier_name'])) {
            $errors['supplier_name'] = 'Supplier name already exists';
        }
        
        return $errors;
    }

    public function update($id, array $data)
    {
        return $this->db->update($this->table, $data, $id);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id');
    }
}
