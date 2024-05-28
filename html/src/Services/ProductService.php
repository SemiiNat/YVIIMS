<?php

namespace App\Services;

use App\Models\Product;

class ProductService {

    private $productModel;

    public function __construct(Product $productModel)
    {
        $this->productModel = $productModel;
    }

    public function getAllAvailableProduct() : Array {
        return $this->productModel->findAllBy("is_deleted", 0);
    }
}