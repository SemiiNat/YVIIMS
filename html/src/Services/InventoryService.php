<?php

namespace App\Services;

use App\Helper\Container;
use App\Models\Inventory;
use App\Helper\DatabaseHelper;

class InventoryService
{
    private Inventory $inventoryModel;
    private DatabaseHelper $db;

    public function __construct(Inventory $inventoryModel)
    {
        $this->inventoryModel = $inventoryModel;
        $this->db = Container::get(DatabaseHelper::class);
    }

    public function getAllInventory(): array
    {
        $sql = "SELECT inventory.*, product.product_name 
                FROM inventory 
                LEFT JOIN product ON inventory.product_id = product.id";
        return $this->db->getMany($sql);
    }

    public function getInventoryById(int $id): ?array
    {
        $sql = "SELECT inventory.*, product.product_name 
                FROM inventory 
                LEFT JOIN product ON inventory.product_id = product.id 
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

            $inventoryId = $this->inventoryModel->save($data);
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
}
