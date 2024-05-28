<?php


namespace App\Controllers;

use App\Http\View;
use App\Services\ProductService;

class ProductController
{
    private $productService;

    public function __construct(ProductService $productService) {
        $this->productService = $productService;
    }

    public function index(): View
    {
        $products = $this->productService->getAllAvailableProduct();
        $contentView = View::make('productPage', ['products' => $products])->render();

        return View::make('dashboard', ['content' => $contentView]);
    }
}
