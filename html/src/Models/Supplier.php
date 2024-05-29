<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Helper\DatabaseHelper;

class Supplier extends BaseModel {

    protected $table = 'supplier';
    protected $validation;
    protected $requiredFields = [
        // 'id',
        "supplier_name",
        "phone_number",
        "email",
    ];

    public function __construct(DatabaseHelper $db){
        parent::__construct($db);
    }

    function validate($data): array {
        $errors = [];

        $missingFields = $this->requiredFields($this->requiredFields, $data);
        if(!empty($missingFields)){
            foreach($missingFields as $field){
                $errors[$field] = "$field as required";
            }
        }

        if (!$this->validation->isUnique($this->table, 'supplier_name', $data['supplier_name'])) {
            $errors['supplier_name'] = 'Supplier name already exists';
        }

        return $errors;
    }
}
