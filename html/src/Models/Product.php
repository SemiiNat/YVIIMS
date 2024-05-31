<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Helper\DatabaseHelper;

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
    }

    public function validate($data): array
    {
        $errors = parent::validate($data);

        if (!$this->validation->isUnique($this->table, 'sku', $data['sku'])) {
            $errors['sku'] = 'SKU already exists';
        }

        return $errors;
    }
}
