<?php

namespace App\Services;

use App\Helper\Container;
use App\Models\Inventory;
use App\Models\Batch;
use App\Helper\DatabaseHelper;

class InventoryService
{
    private Inventory $inventoryModel;
    private Batch $batchModel;
    private DatabaseHelper $db;

    public function __construct(Inventory $inventoryModel, Batch $batchModel)
    {
        $this->inventoryModel = $inventoryModel;
        $this->batchModel = $batchModel;
        $this->db = Container::get(DatabaseHelper::class);
    }

    public function getAllInventory(): array
    {
        $sql = "SELECT inventory.*, product.product_name, batch.sku, batch.manufacturing_date, batch.expiration_date, batch.quantity 
                FROM inventory 
                LEFT JOIN product ON inventory.product_id = product.id
                LEFT JOIN batch ON inventory.product_id = batch.product_id";
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

            // Save inventory
            $inventoryData = [
                'product_id' => $data['product_id']
                // Add other necessary fields for inventory here if needed
            ];
            $inventoryId = $this->inventoryModel->save($inventoryData);
            if ($inventoryId === false) {
                throw new \Exception("Failed to save inventory");
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

    public function deleteInventory(int $id): bool
    {
        return $this->inventoryModel->delete($id);
    }

    private function generateSKU(int $productId): string
    {
        return 'SKU-' . str_pad($productId, 5, '0', STR_PAD_LEFT) . '-' . time();
    }

    private function calculateExpirationDate(string $manufacturingDate): string
    {
        $manufacturingDateTime = new \DateTime($manufacturingDate);
        $manufacturingDateTime->add(new \DateInterval('P9M')); // Add 9 months
        return $manufacturingDateTime->format('Y-m-d');
    }
}
