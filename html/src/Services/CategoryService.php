<?php

namespace App\Services;

use App\Helper\Container;
use App\Helper\DatabaseHelper;
use App\Models\Category;

class CategoryService {

    private Category $categoryModel;
    private DatabaseHelper $db;
    private $lastErrors = [];

    public function __construct(Category $categoryModel)
    {
        $this->categoryModel = $categoryModel;
        $this->db = Container::get(DatabaseHelper::class);
    }

    public function getCategory() : Array {
        return $this->categoryModel->findAll();
    }

    public function getCategoryById(int $id) {
        return $this->categoryModel->find($id);
    }

    public function createCategory($data): array
    {
        $this->db->beginTransaction();
        $validationErrors = [];
    
        try {
            $validationErrors = $this->categoryModel->validate($data);
    
            if (!empty($validationErrors)) {
                throw new \Exception("Validation Error");
            }
    
            $this->categoryModel->save($data);
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            if ($e->getMessage() === "Validation Error") {
                return $validationErrors;
            }
            throw $e;
        }
    
        return $validationErrors;
    }

    public function getCategoryErrors()
    {
        return $this->lastErrors;
    }
}