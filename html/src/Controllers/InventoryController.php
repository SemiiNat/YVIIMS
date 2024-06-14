<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Http\View;
use App\Services\InventoryService;
use App\Services\ProductService;

class InventoryController
{
    private InventoryService $inventoryService;
    private ProductService $productService;

    public function __construct(
        InventoryService $inventoryService,
        ProductService $productService
    ) {
        $this->inventoryService = $inventoryService;
        $this->productService = $productService;
    }

    public function index(): View
    {
        $inventory = $this->inventoryService->getAllInventory();
        $contentView = View::make('inventory/index', ['inventory' => $inventory])->render();

        return View::make('dashboard', ['content' => $contentView]);
    }

    public function create(): View
    {
        $products = $this->productService->getAllAvailableProduct();
        $contentView = View::make('inventory/create', ['products' => $products])->render();

        return View::make('dashboard', ['content' => $contentView]);
    }

    public function save(Request $request, Response $response)
    {
        $data = $request->getBody();

        $validationError = $this->inventoryService->createInventory($data);

        if (!empty($validationError)) {
            error_log("Validation error: " . json_encode($validationError));
            $response->sendJson($validationError, 422);
            return;
        }

        error_log("Successfully created inventory with data: " . json_encode($data));
        $response->sendJson(["message" => "Inventory created successfully"], 201);
    }

    public function edit(Request $request, Response $response, $id): View
    {
        $batch = $this->inventoryService->getBatchById((int) $id);
        if (!$batch) {
            $response->sendJson(["error" => "Not Found"], 404);
            return View::make('404'); // Ensure you have a 404 view
        }

        $products = $this->productService->getAllAvailableProduct();
        $contentView = View::make('inventory/edit', [
            'inventory' => $batch,
            'products' => $products,
        ])->render();

        return View::make('dashboard', ['content' => $contentView]);
    }

    public function update(Request $request, Response $response, $id)
    {
        error_log("Attempting to update inventory with batch ID: " . $id);
        $data = $request->getBody();
        $data['id'] = (int) $id;
    
        $validationError = $this->inventoryService->updateInventory((int) $id, $data);
    
        if (!empty($validationError)) {
            error_log("Validation error: " . json_encode($validationError));
            $response->sendJson($validationError, 422);
            return;
        }
    
        error_log("Successfully updated inventory with batch ID: " . $id);
        $response->setHeader('Location', '/inventory');
        $response->setBody(''); // Ensure no body is sent
        $response->send(); // Send the response immediately to handle redirection
    }
    
    

    public function delete(Request $request, Response $response, $id)
    {
        error_log("Attempting to delete inventory with batch ID: " . $id);
        $result = $this->inventoryService->deleteInventory((int) $id);

        if (!$result) {
            error_log("Failed to delete inventory with batch ID: " . $id);
            $response->sendJson(["error" => "Failed to delete inventory"], 500);
            return;
        }

        error_log("Successfully deleted inventory with batch ID: " . $id);
        $response->sendJson(["message" => "Inventory deleted successfully"], 200);
    }
}