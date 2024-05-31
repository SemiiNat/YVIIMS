<?php

namespace App\Services;

use App\Helper\Container;
use App\Helper\DatabaseHelper;
use App\Models\Supplier;

class SupplierService {

    private Supplier $supplierModel;
    private DatabaseHelper $db;
    private $lastErrors = [];

    public function __construct(Supplier $supplierModel)
    {
        $this->supplierModel = $supplierModel;
        $this->db = Container::get(DatabaseHelper::class);
    }

    public function getSupplier() : Array {
        return $this->supplierModel->findAll();
    }

    public function getSupplierById(int $id) {
        return $this->supplierModel->find($id);
    }

    public function createSupplier($data): array
    {
        $this->db->beginTransaction();
        $validationErrors = [];
    
        try {
            $validationErrors = $this->supplierModel->validate($data);
    
            if (!empty($validationErrors)) {
                throw new \Exception("Validation Error");
            }
    
            $this->supplierModel->save($data);
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            if ($e->getMessage() === "Validation Error") {
                return $validationErrors;
            }
            throw $e;
        }
    
        return $validationErrors;
    }

    public function deleteSupplier(int $id){
        $this->supplierModel->delete($id);
    }

    public function getSupplierErrors()
    {
        return $this->lastErrors;
    }

    public function updateSupplier($id, $data): array
    {
        $this->db->beginTransaction();
        $validationErrors = [];
    
        try {
            $validationErrors = $this->supplierModel->validate($data);
    
            if (!empty($validationErrors)) {
                throw new \Exception("Validation Error");
            }
    
            $this->supplierModel->update($id, $data);
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            if ($e->getMessage() === "Validation Error") {
                return $validationErrors;
            }
            throw $e;
        }
    
        return $validationErrors;
    }
    
    
    
    

}