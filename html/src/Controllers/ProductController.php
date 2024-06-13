<?php
namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Http\View;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\SupplierService;

class ProductController
{
    private ProductService $productService;
    private CategoryService $categoryService;
    private SupplierService $supplierService;

    public function __construct(
        ProductService $productService,
        CategoryService $categoryService,
        SupplierService $supplierService
    ) {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->supplierService = $supplierService;
    }

    public function index(): View
    {
        $products = $this->productService->getAllAvailableProduct();
        $categories = $this->categoryService->getCategory();
        $contentView = View::make('product/index', ['products' => $products, 'categories' => $categories])->render();

        return View::make('dashboard', ['content' => $contentView]);
    }

    public function create(): View
    {
        $categories = $this->categoryService->getCategory();
        $suppliers = $this->supplierService->getSupplier();
        $contentView = View::make('product/create', [
            'categories' => $categories,
            'suppliers' => $suppliers,
        ])->render();

        return View::make('dashboard', ['content' => $contentView]);
    }

    public function save(Request $request, Response $response)
    {
        $data = $request->getBody();
        unset($data['search_terms']);
        if (isset($data['category_id'])) {
            $data['category_id'] = (int)$data['category_id'];
        }
        if (isset($data['supplier_ids']) && is_array($data['supplier_ids'])) {
            $data['supplier_ids'] = array_map('intval', $data['supplier_ids']);
        } else {
            $data['supplier_ids'] = [];
        }

        // Extract inventory data
        $inventoryData = [
            'quantity' => (int)$data['quantity'],
            'expiration_date' => date('Y-m-d', strtotime($data['manufacturing_date'] . ' + 9 months'))
        ];
        unset($data['quantity']);

        $validationError = $this->productService->createProduct($data, $inventoryData);

        if (!empty($validationError)) {
            $response->sendJson($validationError, 422);
            return;
        }

        $response->sendJson(["message" => "Product created successfully"], 201);
    }

    public function edit(Request $request, Response $response, $id): View
    {
        $product = $this->productService->getProductById((int) $id);
        $categories = $this->categoryService->getCategory();
        $suppliers = $this->supplierService->getSupplier();
        $contentView = View::make('product/edit', [
            'product' => $product,
            'categories' => $categories,
            'suppliers' => $suppliers,
        ])->render();

        return View::make('dashboard', ['content' => $contentView]);
    }

    public function update(Request $request, Response $response, $id)
    {
        $data = $request->getBody();
        $data['id'] = (int) $id; // Ensure the ID is set for the update

        unset($data['search_terms']);
        if (isset($data['category_id'])) {
            $data['category_id'] = (int)$data['category_id'];
        }
        if (isset($data['supplier_ids']) && is_array($data['supplier_ids'])) {
            $data['supplier_ids'] = array_map('intval', $data['supplier_ids']);
        } else {
            $data['supplier_ids'] = [];
        }

        // Extract inventory data
        $inventoryData = [
            'quantity' => (int)$data['quantity'],
            'expiration_date' => date('Y-m-d', strtotime($data['manufacturing_date'] . ' + 9 months'))
        ];
        unset($data['quantity']);

        $validationError = $this->productService->updateProduct($data, $inventoryData);

        if (!empty($validationError)) {
            $response->sendJson($validationError, 422);
            return;
        }

        $response->sendJson(["message" => "Product updated successfully"], 200);
    }
}
