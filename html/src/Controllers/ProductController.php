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
        $validationError = $this->productService->createProduct($data);

        if (!empty($validationError)) {
            $response->sendJson($validationError, 422);
            return;
        }

        $response->sendJson(["message" => "Product created successfully"], 201);
    }
}
