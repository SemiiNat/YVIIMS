<?php

namespace App\Services;

use App\Helper\DatabaseHelper;

class InventoryService
{
    private $db;

    public function __construct(DatabaseHelper $db)
    {
        $this->db = $db;
    }

    public function createInventory(array $data): int|bool
    {
        return $this->db->create('inventory', $data);
    }

    public function updateInventory(array $data): bool
    {
        $productId = $data['product_id'];
        unset($data['product_id']);
        return $this->db->update('inventory', $data, $productId);
    }
}
