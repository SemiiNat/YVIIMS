<?php

namespace App\Models;

use App\Helper\DatabaseHelper;

/**
 * The BaseModel class serves as the base model for all other models in the application.
 * It provides common database operations and query building methods.
 */
abstract class BaseModel
{
    /**
     * The database helper instance.
     *
     * @var DatabaseHelper
     */
    protected $db;

    /**
     * The name of the database table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The primary key column name of the database table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The list of required fields for validation.
     *
     * @var array
     */
    protected $requiredFields = [];

    /**
     * The base query string.
     *
     * @var string
     */
    protected $query;

    /**
     * The parameter bindings for the query.
     *
     * @var array
     */
    protected $bindings = [];

    /**
     * Create a new BaseModel instance.
     *
     * @param DatabaseHelper $db The database helper instance.
     */
    public function __construct(DatabaseHelper $db)
    {
        $this->db = $db;
        $this->query = "SELECT * FROM {$this->table}";
    }

    /**
     * Find a record by its primary key value.
     *
     * @param mixed $value The value of the primary key.
     * @return mixed The found record or null if not found.
     */
    public function find($value = null)
    {
        if ($value !== null) {
            $this->where($this->primaryKey, '=', $value);
        }
        $result = $this->db->getMany($this->query, $this->bindings);
        return $value === null ? $result : $result[0] ?? null;
    }

    /**
     * Delete a record by its primary key value.
     *
     * @param mixed $id The value of the primary key.
     * @return bool True if the record is deleted successfully, false otherwise.
     */
    public function delete($id)
    {
        return $this->db->hard_delete($this->table, $id);
    }

    /**
     * Save a record to the database.
     *
     * @param array $data The data to be saved.
     * @return bool True if the record is saved successfully, false otherwise.
     */
    public function save(array $data)
    {
        if (isset($data[$this->primaryKey]) && $data[$this->primaryKey] !== null) {
            return $this->db->update($this->table, $data, $data[$this->primaryKey]);
        } else {
            return $this->db->create($this->table, $data);
        }
    }

    /**
     * Find all records in the database table.
     *
     * @return array The array of found records.
     */
    public function findAll()
    {
        return $this->db->getMany("SELECT * FROM {$this->table}");
    }

    /**
     * Find all records in the database table that match the given column and value.
     *
     * @param string $column The column name to search.
     * @param mixed $value The value to search.
     * @return mixed The found record or null if not found.
     */
    public function findAllBy($column, $value)
    {
        $this->where($column, '=', $value);
        return $this->find();
    }

    /**
     * Add a WHERE condition to the query.
     *
     * @param string $column The column name.
     * @param string $operator The comparison operator.
     * @param mixed $value The value to compare.
     * @return $this The current BaseModel instance.
     */
    public function where($column, $operator, $value)
    {
        $condition = "{$column} {$operator} ?";
        if (strpos($this->query, 'WHERE') !== false) {
            $this->query .= " AND {$condition}";
        } else {
            $this->query .= " WHERE {$condition}";
        }
        $this->bindings[] = $value;
        return $this;
    }

    /**
     * Validate the given data against the required fields.
     *
     * @param array $data The data to be validated.
     * @return array The array of validation errors.
     */
    public function validate(array $data)
    {
        $errors = [];
        foreach ($this->requiredFields as $field) {
            if (empty($data[$field])) {
                $errors[$field] = "{$field} is required";
            }
        }
        return $errors;
    }

    /**
     * Bind the parameters to a prepared statement.
     *
     * @param mixed $stmt The prepared statement.
     * @param array $params The parameters to bind.
     * @throws \Exception If failed to prepare the statement.
     */
    protected function bindParams($stmt, $params)
    {
        if (!$stmt) {
            throw new \Exception("Failed to prepare statement.");
        }
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } elseif (is_string($param)) {
                $types .= 's';
            } else {
                $types .= 'b';
            }
        }
        $stmt->bind_param($types, ...$params);
    }

    /**
     * Get the related records from a one-to-many relationship.
     *
     * @param string $relatedModel The related model class name.
     * @param string $foreignKey The foreign key column name.
     * @return array The array of related records.
     */
    public function hasMany($relatedModel, $foreignKey)
    {
        $relatedInstance = new $relatedModel();
        $sql = "SELECT * FROM {$relatedInstance->table} WHERE {$foreignKey} = ?";
        return $this->db->getMany($sql, [$this->{$this->primaryKey}]);
    }

    /**
     * Defines a one-to-one relationship between the current model and a related model.
     *
     * @param string $relatedModel The class name of the related model.
     * @param string $localKey The name of the local key column in the current model's table.
     * @param string $foreignKey The name of the foreign key column in the related model's table.
     * @return mixed The related model instance or null if no related model is found.
     */
    public function hasOne($relatedModel, $localKey, $foreignKey)
    {
        $relatedInstance = new $relatedModel($this->db);
        $sql = "SELECT * FROM {$relatedInstance->table} WHERE {$foreignKey} = ?";
        return $this->db->getOne($sql, [$this->{$localKey}]);
    }

    /**
     * Get the related record from a many-to-one relationship.
     *
     * @param string $relatedModel The related model class name.
     * @param string $foreignKey The foreign key column name.
     * @param string $ownerKey The owner key column name.
     * @return mixed The related record or null if not found.
     */
    public function belongsTo($relatedModel, $foreignKey, $ownerKey = 'id')
    {
        $relatedInstance = new $relatedModel();
        $sql = "SELECT * FROM {$relatedInstance->table} WHERE {$ownerKey} = ?";
        return $this->db->getOne($sql, [$this->{$foreignKey}]);
    }

    /**
     * Executes a custom SQL query.
     *
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return mixed The result of the query execution.
     */
    public function customQuery($sql, $params = [])
    {
        if (strpos(strtoupper($sql), 'SELECT') !== false) {
            return $this->db->getMany($sql, $params);
        } else {
            return $this->db->query($sql, $params);
        }
    }

    /**
     * Soft deletes a record by its ID.
     *
     * @param int $id The ID of the record to delete.
     * @return bool True if the record was successfully soft deleted, false otherwise.
     */
    public function soft_delete($id)
    {
        return $this->db->soft_delete($this->table, $id);
    }
}
