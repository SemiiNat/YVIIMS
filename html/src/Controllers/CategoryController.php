<?php

namespace App\Controllers;

use App\Http\Redirect;
use App\Http\Request;
use App\Http\View;
use App\Services\CategoryService;

class CategoryController
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService){
        $this->categoryService = $categoryService;
    }

    public function index(): View
    {
        $categories = $this->categoryService->getCategory();
        $contentView = View::make('categoryPage', ['categories' => $categories])->render();

        return View::make('dashboard', ['content' => $contentView]);
    }

    public function save(Request $request)
    {
        $data = $request->getBody();

        $this->categoryService->createCategory($data);
        Redirect::to('/category');
    }
}
