<?php


namespace App\Controllers;

use App\Http\View;
use App\Services\CategoryService;
use App\Services\ProductService;

class ProductController
{
    private ProductService $productService;
    private CategoryService $categoryService;

    public function __construct(ProductService $productService, CategoryService $categoryService) {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    public function index(): View
    {
        $products = $this->productService->getAllAvailableProduct();
        $categories = $this->categoryService->getCategory();
        $contentView = View::make('productPage', ['products' => $products,'categories' => $categories])->render();

        return View::make('dashboard', ['content' => $contentView]);
    }
}
