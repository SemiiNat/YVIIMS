<?php

namespace App\Services;

use App\Helper\Container;
use App\Models\Product;
use App\Helper\DatabaseHelper;

class ProductService
{

    private Product $productModel;
    private DatabaseHelper $db;

    public function __construct(Product $productModel)
    {
        $this->productModel = $productModel;
        $this->db = Container::get(DatabaseHelper::class);
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
        $this->db->beginTransaction();
        $validationErrors = [];

        try {
            $validationErrors = $this->productModel->validate($data);

            if (!empty($validationErrors)) {
                throw new \Exception("Validation Error");
            }

            // Remove supplier_ids from data
            $supplierIds = $data['supplier_ids'] ?? [];
            unset($data['supplier_ids']);

            $productId = $this->productModel->save($data);
            if ($productId === false) {
                throw new \Exception("Database Error");
            }

            // Attach suppliers
            if (!empty($supplierIds)) {
                $this->productModel->attachSuppliers($productId, $supplierIds);
            }

            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollback();
            error_log($e->getMessage());
            if ($e->getMessage() === "Validation Error") {
                return $validationErrors;
            }
            return ['error' => $e->getMessage()];
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
