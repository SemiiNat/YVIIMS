<?php

namespace App\Models;

use App\Helper\DatabaseHelper;

/**
 * The BaseModel class serves as the base class for all models in the application.
 * It provides common database operations such as find, delete, save, findAll, and findAllBy.
 */
abstract class BaseModel
{
    protected $db;
    protected $table;
    protected $requiredFields;

    /**
     * Constructs a new instance of the BaseModel class.
     *
     * @param DatabaseHelper $db The database helper instance.
     */
    public function __construct(DatabaseHelper $db)
    {
        $this->db = $db;
    }

    /**
     * Retrieves a record from the database based on the given ID.
     *
     * @param int $id The ID of the record to retrieve.
     * @return array|null The retrieved record, or null if not found.
     */
    public function find($id)
    {
        return $this->db->getOne("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    }

    /**
     * Deletes a record from the database based on the given ID.
     *
     * @param int $id The ID of the record to delete.
     * @return bool True if the record was successfully deleted, false otherwise.
     */
    public function delete($id)
    {
        return $this->db->hard_delete($this->table, $id);
    }

    /**
     * Saves a record to the database.
     * If the 'id' field is set and not null, the record will be updated.
     * Otherwise, a new record will be inserted.
     *
     * @param array $data The data to save.
     * @return bool True if the record was successfully saved, false otherwise.
     */
    public function save(array $data)
    {
        if (isset($data['id']) && $data['id'] !== null) {
            return $this->db->update($this->table, $data, $data['id']);
        } else {
            return $this->db->create($this->table, $data);
        }
    }

    /**
     * Retrieves all records from the database.
     *
     * @return array The retrieved records.
     */
    public function findAll()
    {
        return $this->db->getMany("SELECT * FROM {$this->table}");
    }

    /**
     * Retrieves records from the database based on the given column and value.
     *
     * @param string $column The column to search.
     * @param mixed $value The value to match.
     * @return array The retrieved records.
     */
    public function findAllBy($column, $value)
    {
        $query = "SELECT * FROM {$this->table} WHERE {$column} = ?";
        try {
            $result = $this->db->getMany($query, [$value]);
            if ($result === false) {
                throw new \Exception("Query failed: $query");
            }
            return $result;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    /**
     * Checks if the required fields are present in the given data.
     *
     * @param array $requiredFields The required fields.
     * @param array $data The data to check.
     * @return array The missing fields, if any.
     */
    protected function requiredFields($requiredFields, $data)
    {
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $data) || empty($data[$field])) {
                $missingFields[] = $field;
            }
        }

        return $missingFields;
    }
}
