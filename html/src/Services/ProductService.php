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

    public function createProduct($data, $inventoryData): array
    {
        $this->db->beginTransaction();
        $validationErrors = [];
    
        try {
            $validationErrors = $this->productModel->validate($data);
    
            if (!empty($validationErrors)) {
                throw new \Exception("Validation Error");
            }
    
            // Generate SKU
            $skuPrefix = "YVI-{$data['category_id']}-{$data['product_name']}-";
            $latestProduct = $this->productModel->getLatestProductBySKU($skuPrefix);
            $sequenceId = 1;
            if ($latestProduct) {
                $latestSku = $latestProduct['sku'];
                $sequenceId = intval(substr($latestSku, -3)) + 1;
            }
            $data['sku'] = $skuPrefix . date('Y', strtotime($data['manufacturing_date'])) . '-A' . str_pad($sequenceId, 3, '0', STR_PAD_LEFT);
    
            // Remove supplier_ids from data
            $supplierIds = $data['supplier_ids'] ?? [];
            unset($data['supplier_ids']);
    
            $productId = $this->db->saveProductAndInventory($data, $inventoryData);
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
        return $this->productModel->soft_delete($id);
    }

    public function updateProduct($data, $inventoryData): array
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

            // Update inventory
            $this->db->updateInventoryByProductId($data['id'], $inventoryData);

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
