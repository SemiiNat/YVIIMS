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

    public function createCategory($data) : void {

        $this->db->beginTransaction();

        try{
            $validationErrors = $this->categoryModel->validate($data);

            $this->categoryModel->save($data);

            if (!empty($validationErrors)) {
                $this->lastErrors = $validationErrors;
                throw new \Exception("Validation Error");
            }
            $this->db->commit();
        } catch(\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getCategoryErrors()
    {
        return $this->lastErrors;
    }
}