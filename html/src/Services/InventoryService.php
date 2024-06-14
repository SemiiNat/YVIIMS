<?php

namespace App\Services;

use App\Helper\Container;
use App\Models\Inventory;
use App\Models\Batch;
use App\Helper\DatabaseHelper;
use App\Services\ProductService;

class InventoryService
{
    private Inventory $inventoryModel;
    private Batch $batchModel;
    private ProductService $productService;
    private DatabaseHelper $db;

    public function __construct(Inventory $inventoryModel, Batch $batchModel, ProductService $productService)
    {
        $this->inventoryModel = $inventoryModel;
        $this->batchModel = $batchModel;
        $this->productService = $productService;
        $this->db = Container::get(DatabaseHelper::class);
    }

    public function getAllInventory(): array
    {
        $sql = "SELECT batch.*, product.product_name 
                FROM batch 
                LEFT JOIN product ON batch.product_id = product.id";
        return $this->db->getMany($sql);
    }

    public function getInventoryById(int $id): ?array
    {
        $sql = "SELECT inventory.*, product.product_name, batch.sku, batch.manufacturing_date, batch.expiration_date, batch.quantity 
                FROM inventory 
                LEFT JOIN product ON inventory.product_id = product.id
                LEFT JOIN batch ON inventory.product_id = batch.product_id 
                WHERE inventory.id = ?";
        return $this->db->getOne($sql, [$id]);
    }

    public function createInventory(array $data): array
    {
        $this->db->beginTransaction();
        $validationErrors = [];

        try {
            $validationErrors = $this->inventoryModel->validate($data);

            if (!empty($validationErrors)) {
                throw new \Exception("Validation Error");
            }

            // Generate SKU and expiration date
            $sku = $this->generateSKU($data['product_id']);
            $expirationDate = $this->calculateExpirationDate($data['manufacturing_date']);
            $data['sku'] = $sku;
            $data['expiration_date'] = $expirationDate;

            // Save batch
            $batchData = [
                'product_id' => $data['product_id'],
                'sku' => $sku,
                'manufacturing_date' => $data['manufacturing_date'],
                'expiration_date' => $expirationDate,
                'quantity' => $data['quantity']
            ];
            $batchId = $this->batchModel->save($batchData);
            if ($batchId === false) {
                throw new \Exception("Failed to save batch");
            }
            error_log("Successfully saved batch with data: " . json_encode($batchData));

            // Save inventory
            $inventoryData = [
                'product_id' => $data['product_id']
                // Add other necessary fields for inventory here if needed
            ];
            $inventoryId = $this->inventoryModel->save($inventoryData);
            if ($inventoryId === false) {
                throw new \Exception("Failed to save inventory");
            }
            error_log("Successfully saved inventory with data: " . json_encode($inventoryData));

            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollback();
            error_log("Error creating inventory: " . $e->getMessage());
            error_log("Data: " . json_encode($data));
            if ($e->getMessage() === "Validation Error") {
                return $validationErrors;
            }
            return ['error' => "Database Error: " . $e->getMessage()];
        }

        return $validationErrors;
    }

    public function updateInventory(int $id, array $data): array
    {
        $this->db->beginTransaction();
        $validationErrors = [];

        try {
            $data['id'] = $id;
            $validationErrors = $this->inventoryModel->validate($data);

            if (!empty($validationErrors)) {
                throw new \Exception("Validation Error");
            }

            $result = $this->inventoryModel->update($data);
            if ($result === false) {
                throw new \Exception("Failed to update inventory");
            }
            error_log("Successfully updated inventory with ID: " . $id);

            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollback();
            error_log("Error updating inventory: " . $e->getMessage());
            if ($e->getMessage() === "Validation Error") {
                return $validationErrors;
            }
            return ['error' => "Database Error: " . $e->getMessage()];
        }

        return $validationErrors;
    }

    public function deleteInventory(int $batchId): bool
    {
        $this->db->beginTransaction();

        try {
            // Get the product_id associated with the batch
            $batch = $this->batchModel->getById($batchId);
            if (!$batch) {
                throw new \Exception("Batch not found with ID: " . $batchId);
            }

            $productId = $batch['product_id'];

            // Delete the batch record
            $batchDeleteResult = $this->batchModel->delete($batchId);
            if (!$batchDeleteResult) {
                throw new \Exception("Failed to delete batch with ID: " . $batchId);
            }
            error_log("Successfully deleted batch with ID: " . $batchId);

            // Delete the related inventory record based on product_id
            $inventoryDeleteResult = $this->inventoryModel->deleteByProductId($productId);
            if (!$inventoryDeleteResult) {
                throw new \Exception("Failed to delete inventory for product_id: " . $productId);
            }
            error_log("Successfully deleted inventory for product_id: " . $productId);

            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollback();
            error_log("Error deleting inventory: " . $e->getMessage());
            return false;
        }

        return true;
    }

    private function generateSKU(int $productId): string
    {
        $product = $this->db->getOne("SELECT p.product_name, c.category_name FROM product p JOIN category c ON p.category_id = c.id WHERE p.id = ?", [$productId]);

        $categoryAbbreviation = strtoupper(substr($product['category_name'], 0, 5));
        $productAbbreviation = strtoupper(implode('', array_map(function ($word) {
            return $word[0];
        }, explode(' ', $product['product_name']))));
        $year = date('Y');

        return 'YVI-' . $categoryAbbreviation . '-' . $productAbbreviation . '-' . $year;
    }

    private function getProductAbbreviation(string $productName): string
    {
        $words = explode(' ', $productName);
        $abbreviation = '';

        foreach ($words as $word) {
            $abbreviation .= strtoupper($word[0]);
        }

        return $abbreviation;
    }

    private function getCategoryAbbreviation(string $categoryName): string
    {
        return strtoupper(substr($categoryName, 0, 5));
    }

    private function calculateExpirationDate(string $manufacturingDate): string
    {
        $manufacturingDateTime = new \DateTime($manufacturingDate);
        $manufacturingDateTime->add(new \DateInterval('P9M')); // Add 9 months
        return $manufacturingDateTime->format('Y-m-d');
    }
}
