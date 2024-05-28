<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Helper\DatabaseHelper;

class Product extends BaseModel {

    protected $table = 'product';
    protected $validation;
    protected $requiredFields = [
        'category_id',
        "sku",
        "product_name",
    ];

    public function __construct(DatabaseHelper $db){
        parent::__construct($db);
    }
}