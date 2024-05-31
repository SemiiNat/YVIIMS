<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{

    private $productModel;

    public function __construct(Product $productModel)
    {
        $this->productModel = $productModel;
    }

    public function getAllAvailableProduct(): array
    {
        return $this->productModel->where("is_deleted", "=", 0)->find();
    }

    public function getProductById(int $id)
    {
        return $this->productModel->find($id);
    }

    public function createProduct($data): array
    {
        $validationErrors = [];

        try {
            $validationErrors = $this->productModel->validate($data);

            if (!empty($validationErrors)) {
                throw new \Exception("Validation Error");
            }

            $this->productModel->save($data);
        } catch (\Exception $e) {
            if ($e->getMessage() === "Validation Error") {
                return $validationErrors;
            }
            throw $e;
        }

        return $validationErrors;
    }

    /**
     * Soft deletes a product by its ID.
     *
     * @param int $id The ID of the product to be soft deleted.
     * @return bool Returns true if the product was successfully soft deleted, false otherwise.
     */
    public function softDelete(int $id): bool
    {
        return $this->productModel->soft_delete($id);
    }
}
