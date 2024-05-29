<?php

namespace App\Controllers;

use App\Http\Redirect;
use App\Http\Request;
use App\Http\Response;
use App\Http\View;
use App\Services\SupplierService;

class SupplierController
{
    private SupplierService $supplierService;

    public function __construct(SupplierService $supplierService){
        $this->supplierService = $supplierService;
    }

    public function index(): View
    {
        $contentView = View::make('supplierPage')->render();

        return View::make('dashboard', ['content' => $contentView]);
    }
    public function get(Request $request, Response $response){
        $data = $this->supplierService->getSupplier();

        return $response->sendJson($data);
    }

    public function getById(Request $request, Response $response, int $id)
    {   
        if(empty($id)){
            return $response->sendJson(["error"=>"no id found"], 400);
        }
        
        $supplier = $this->supplierService->getSupplierById($id);
        if (empty($supplier)){
            return $response->sendJson(["error"=> "Supplier with $id not found"],404);
        }
        return $response->sendJson($supplier);
    }

    public function delete(Request $request, Response $response, int $id) {
        if(empty($id)){
            return $response->sendJson(["error"=> "no id found"],404);
        }

        $supplier = $this->supplierService->getSupplierById($id);

        if (empty($category)){
            return $response->sendJson(["error"=> "Supplier with $id not found"],404);
        }

        $this->supplierService->deleteSupplier($id);
    }

    public function save(Request $request,Response $response)
    {
        $data = $request->getBody();

        $validationError = $this->supplierService->createSupplier($data);

        if (!empty($validationError)) {
            return $response->sendJson($validationError, 422);
        }

        return $response->sendJson(['success'=> true], 201);
    }
}
