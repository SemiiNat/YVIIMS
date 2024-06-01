<?php

namespace App\Models;

use App\Helper\DatabaseHelper;
use App\Helper\Validation;

class Category extends BaseModel
{
    protected $table = 'category';
    protected $primaryKey = 'id';
    protected $requiredFields = ['category_name'];
    protected $validation;

    public function __construct(DatabaseHelper $db)
    {
        parent::__construct($db);
        $this->validation = new Validation($db);
    }

    public function validate($data): array
    {
        $errors = parent::validate($data);

        if (!$this->validation->isUnique($this->table, 'category_name', $data['category_name'])) {
            $errors['category_name'] = 'Category name already exists';
        }

        return $errors;
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
