<?php

namespace App\Models;

use App\Helper\DatabaseHelper;
use App\Helper\Validation;

class Category extends BaseModel {

    protected $table = 'category';
    protected $validation;
    protected $requiredFields = [
        'category_name',
    ];

    public function __construct(DatabaseHelper $db) {
        parent::__construct($db);
        $this->validation = new Validation($db);
    }

    function validate($data): array {
        $errors = [];

        $missingFields = $this->requiredFields($this->requiredFields, $data);
        if(!empty($missingFields)){
            foreach($missingFields as $field){
                $errors[$field] = "$field as required";
            }
        }

        if (!$this->validation->isUnique($this->table, 'category_name', $data['category_name'])) {
            $errors['category_name'] = 'Category name already exists';
        }

        return $errors;
    }
}