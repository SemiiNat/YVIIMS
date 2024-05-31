<?php

namespace App\Models;

use App\Helper\DatabaseHelper;
use App\Helper\Validation;

class Supplier extends BaseModel
{
    protected $table = 'supplier';
    protected $validation;
    protected $requiredFields = [
        "supplier_name",
        "phone_number",
        "email",
    ];

    public function __construct(DatabaseHelper $db)
    {
        parent::__construct($db);
        $this->validation = new Validation($db);
    }

    function validate($data): array
    {
        $errors = [];

        $missingFields = $this->requiredFields($this->requiredFields, $data);
        if (!empty($missingFields)) {
            foreach ($missingFields as $field) {
                $errors[$field] = "$field as required";
            }
        }

        if (!$this->validation->isUnique($this->table, 'supplier_name', $data['supplier_name'])) {
            $errors['category_name'] = 'Category name already exists';
        }

        return $errors;
    }
}
