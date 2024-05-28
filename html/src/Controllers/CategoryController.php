<?php

namespace App\Controllers;

use App\Http\Redirect;
use App\Http\Request;
use App\Http\Response;
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
        $contentView = View::make('categoryPage')->render();

        return View::make('dashboard', ['content' => $contentView]);
    }
    public function get(Request $request, Response $response){
        $data = $this->categoryService->getCategory();

        return $response->sendJson($data);
    }

    public function getById(Request $request, Response $response, int $id)
    {   
        if(empty($id)){
            return $response->sendJson(["error"=>"no id found"], 400);
        }
        
        $category = $this->categoryService->getCategoryById($id);
        if (empty($category)){
            return $response->sendJson(["error"=> "category with $id not found"],404);
        }
        return $response->sendJson($category);
    }

    public function save(Request $request,Response $response)
    {
        $data = $request->getBody();

        $validationError = $this->categoryService->createCategory($data);

        if (!empty($validationError)) {
            return $response->sendJson($validationError, 422);
        }

        return $response->sendJson(['success'=> true], 201);
    }
}
