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
        $sql = "SELECT product.*, category.category_name 
                FROM product 
                LEFT JOIN category ON product.category_id = category.id 
                WHERE product.is_deleted = 0";
        return $this->db->getMany($sql);
    }

    public function getCategoryNameById(int $categoryId): string
    {
        $sql = "SELECT category_name FROM category WHERE id = ?";
        $params = [$categoryId];
        $category = $this->db->getOne($sql, $params);
        return $category['category_name'] ?? '';
    }

    public function getProductById(int $id)
    {
        $sql = "SELECT product.*, GROUP_CONCAT(supplier.id) AS supplier_ids
                FROM product 
                LEFT JOIN supplierProduct ON product.id = supplierProduct.product_id 
                LEFT JOIN supplier ON supplierProduct.supplier_id = supplier.id 
                WHERE product.id = ?
                GROUP BY product.id";
        $params = [$id];
        return $this->db->getOne($sql, $params);
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
                throw new \Exception("Failed to save product");
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
            return ['error' => "Database Error: " . $e->getMessage()];
        }

        return $validationErrors;
    }

    public function softDelete(int $id): bool
    {
        return $this->productModel->update(['id' => $id, 'is_deleted' => 1]);
    }

    public function updateProduct($data): array
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

            $productId = $this->productModel->update($data);
            if ($productId === false) {
                throw new \Exception("Database Error");
            }

            // Attach suppliers
            if (!empty($supplierIds)) {
                $this->productModel->attachSuppliers($data['id'], $supplierIds);
            }

            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollback();
            error_log($e->getMessage());
            if ($e->getMessage() === "Validation Error") {
                return $validationErrors;
            }
            return ['error' => "Database Error: " . $e->getMessage()];
        }

        return $validationErrors;
    }
}
