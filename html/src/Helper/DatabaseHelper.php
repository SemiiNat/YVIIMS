<?php

namespace App\Helper;

use mysqli;

/**
 * Class DatabaseHelper
 *
 * A helper class for interacting with the database using MySQLi.
 */
class DatabaseHelper
{
    protected $host = null;
    protected $password = null;
    protected $database = null;
    protected $user = null;
    protected static $instance = null;
    public $con = null;

    /**
     * DatabaseHelper constructor.
     *
     * Initializes the database connection using the provided environment variables.
     *
     * @throws \Exception if there is an error connecting to the database.
     */
    private function __construct()
    {
        $this->host = getenv('MYSQL_HOST');
        $this->password = getenv('MYSQL_ROOT_PASSWORD');
        $this->database = getenv('MYSQL_DATABASE');
        $this->user = getenv('MYSQL_USER');
        $this->con = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($this->con->connect_error) {
            throw new \Exception('Connection error: ' . $this->con->connect_error);
        }
    }

    /**
     * Get the instance of the DatabaseHelper class.
     *
     * @return DatabaseHelper The instance of the DatabaseHelper class.
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DatabaseHelper();
        }
        return self::$instance;
    }

    /**
     * Execute a SELECT query and return multiple rows.
     *
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return array The result set as an array of associative arrays.
     */
    public function getMany($sql, $params = [])
    {
        $stmt = $this->con->prepare($sql);
        if (!$stmt) {
            error_log('Failed to prepare statement: ' . $this->con->error);
            return [];
        }
        if ($params) {
            $this->bindParams($stmt, $params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Execute a SELECT query and return a single row.
     *
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return array|null The result set as an associative array or null if no rows are found.
     */
    public function getOne($sql, $params = [])
    {
        $stmt = $this->con->prepare($sql);
        if (!$stmt) {
            error_log('Failed to prepare statement: ' . $this->con->error);
            return null;
        }
        if ($params) {
            $this->bindParams($stmt, $params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Execute a query that does not return a result set.
     *
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return bool True if the query was successful, false otherwise.
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->con->prepare($sql);
        if (!$stmt) {
            error_log('Failed to prepare statement: ' . $this->con->error);
            return false;
        }
        if ($params) {
            $this->bindParams($stmt, $params);
        }
        $stmt->execute();
    }

    /**
     * Insert a new row into the specified table.
     *
     * @param string $table The name of the table.
     * @param array $data An associative array of column names and values.
     * @return int|bool The ID of the inserted row if successful, false otherwise.
     */
    public function create($table, array $data)
    {
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = implode(', ', array_fill(0, count($keys), '?'));
        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
        $stmt = $this->con->prepare($sql);
        if (!$stmt) {
            error_log('Failed to prepare statement: ' . $this->con->error);
            return false;
        }
        $this->bindParams($stmt, array_values($data));
        $result = $stmt->execute();
        return $result ? $this->con->insert_id : false;
    }

    /**
     * Update a row in the specified table.
     *
     * @param string $table_name The name of the table.
     * @param array $update_array An associative array of column names and new values.
     * @param int $id The ID of the row to update.
     * @return bool True if the update was successful, false otherwise.
     */
    public function update($table_name, $update_array, $conditions)
    {
        $table_fields = $this->getMany("DESCRIBE {$table_name}");
        if (empty($table_fields) || empty($update_array)) {
            return false;
        }
        $table_fields_array = [];
        foreach ($table_fields as $table_field) {
            $table_fields_array[] = $table_field['Field'];
        }
        $updates = "";
        $params = [];
        foreach ($update_array as $key => $value) {
            if (in_array($key, $table_fields_array)) {
                $updates .= "`{$key}` = ?, ";
                $params[] = $value;
            }
        }
        $updates = rtrim($updates, ', ');
    
        $where = [];
        foreach ($conditions as $column => $value) {
            $where[] = "{$column} = ?";
            $params[] = $value;
        }
        $where = implode(' AND ', $where);
    
        $stmt = $this->con->prepare("UPDATE {$table_name} SET {$updates} WHERE {$where}");
        if (!$stmt) {
            error_log('Failed to prepare statement: ' . $this->con->error);
            return false;
        }
        $this->bindParams($stmt, $params);
        return $stmt->execute();
    }
    
    

    /**
     * Soft delete a row in the specified table by setting the "is_deleted" column to 1.
     *
     * @param string $table_name The name of the table.
     * @param int $id The ID of the row to delete.
     * @return bool True if the delete was successful, false otherwise.
     */
    public function soft_delete($table_name, $id)
    {
        $stmt = $this->con->prepare("UPDATE {$table_name} SET is_deleted = 1 WHERE id = ?");
        if (!$stmt) {
            error_log('Failed to prepare statement: ' . $this->con->error);
            return false;
        }
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Hard delete a row from the specified table.
     *
     * @param string $table_name The name of the table.
     * @param int $id The ID of the row to delete.
     * @return bool True if the delete was successful, false otherwise.
     */
    public function hard_delete($table_name, $id)
    {
        $stmt = $this->con->prepare("DELETE FROM {$table_name} WHERE id = ?");
        if (!$stmt) {
            error_log('Failed to prepare statement: ' . $this->con->error);
            return false;
        }
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Bind the parameters to a prepared statement.
     *
     * @param mysqli_stmt $stmt The prepared statement.
     * @param array $params The parameters to bind.
     * @throws \Exception if there is an error preparing the statement.
     */
    private function bindParams($stmt, $params)
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
     * Begin a database transaction.
     */
    public function beginTransaction()
    {
        $this->con->begin_transaction();
    }

    /**
     * Commit the current database transaction.
     */
    public function commit()
    {
        $this->con->commit();
    }

    /**
     * Rollback the current database transaction.
     */
    public function rollback()
    {
        $this->con->rollback();
    }
}
