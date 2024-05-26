<?php

namespace App\Helper;

use App\Helper\EnvHelper;

/**
 * The DatabaseHelper class provides methods for interacting with the database.
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
     * Constructs a new DatabaseHelper instance.
     *
     * @throws \Exception if there is a connection error.
     */
    private function __construct()
    {
        EnvHelper::load();
        $this->host = getenv('MYSQL_HOST');
        $this->password = getenv('MYSQL_ROOT_PASSWORD');
        $this->database = getenv('MYSQL_DATABASE');
        $this->user = getenv('MYSQL_USER');
        $this->con = new \mysqli($this->host, $this->user, $this->password, $this->database);

        if ($this->con->connect_error) {
            throw new \Exception('Connection error: ' . $this->con->connect_error);
        }
    }

    /**
     * Returns the singleton instance of DatabaseHelper.
     *
     * @return DatabaseHelper The singleton instance.
     * @throws \Exception if there is an error creating the instance.
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            try {
                self::$instance = new DatabaseHelper();
            } catch (\Exception $e) {
                error_log($e->getMessage());
                throw $e;
            }
        }
        return self::$instance;
    }

    /**
     * Closes the database connection when the object is destroyed.
     */
    public function __destruct()
    {
        $this->closeConnection();
    }

    /**
     * Closes the database connection.
     */
    protected function closeConnection()
    {
        if ($this->con != null) {
            $this->con->close();
            $this->con = null;
        }
    }

    /**
     * Executes a SELECT query and returns multiple rows from the database.
     *
     * @param string $sql The SQL query.
     * @param array $params The query parameters.
     * @return array The result rows.
     */
    public function getMany($sql, $params = [])
    {
        $stmt = $this->con->prepare($sql);

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
     * Executes a query that does not return a result set.
     *
     * @param string $sql The SQL query.
     * @param array $params The query parameters.
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->con->prepare($sql);

        if ($params) {
            $this->bindParams($stmt, $params);
        }

        $stmt->execute();
    }

    /**
     * Executes a SELECT query and returns a single row from the database.
     *
     * @param string $sql The SQL query.
     * @param array $params The query parameters.
     * @return array|null The result row or null if no row is found.
     */
    public function getOne($sql, $params = [])
    {
        $stmt = $this->con->prepare($sql);

        if ($params) {
            $this->bindParams($stmt, $params);
        }

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    /**
     * Binds query parameters to a prepared statement.
     *
     * @param \mysqli_stmt $stmt The prepared statement.
     * @param array $params The query parameters.
     */
    private function bindParams($stmt, $params)
    {
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
     * Inserts a new row into the specified table.
     *
     * @param string $table The table name.
     * @param array $data The data to be inserted.
     * @return int|false The inserted row ID or false if the insertion fails.
     */
    public function create($table, array $data)
    {
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = implode(', ', array_fill(0, count($keys), '?'));

        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";

        $stmt = $this->con->prepare($sql);
        $this->bindParams($stmt, array_values($data));
        $result = $stmt->execute();

        if ($result === false) {
            error_log('Error: ' . $stmt->error);
            return false;
        }

        return $this->con->insert_id;
    }

    /**
     * Updates a row in the specified table.
     *
     * @param string $table_name The table name.
     * @param array $update_array The data to be updated.
     * @param int $id The ID of the row to be updated.
     * @return bool True if the update is successful, false otherwise.
     */
    public function update($table_name, $update_array, $id)
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
        $params[] = $id; // For WHERE clause
        $stmt = $this->con->prepare("UPDATE {$table_name} SET {$updates} WHERE id = ?");
        $this->bindParams($stmt, $params);

        return $stmt->execute();
    }

    /**
     * Begins a database transaction.
     */
    public function beginTransaction()
    {
        $this->con->begin_transaction();
    }

    /**
     * Commits a database transaction.
     */
    public function commit()
    {
        $this->con->commit();
    }

    /**
     * Rolls back a database transaction.
     */
    public function rollback()
    {
        $this->con->rollback();
    }

    /**
     * Soft deletes a row in the specified table.
     *
     * @param string $table_name The table name.
     * @param int $id The ID of the row to be soft deleted.
     * @return bool True if the soft delete is successful, false otherwise.
     */
    public function soft_delete($table_name, $id)
    {
        $stmt = $this->con->prepare("UPDATE {$table_name} SET is_deleted = 1 WHERE id = ?");
        $stmt->bind_param('s', $id);
        return $stmt->execute();
    }

    /**
     * Hard deletes a row in the specified table.
     *
     * @param string $table_name The table name.
     * @param int $id The ID of the row to be hard deleted.
     * @return bool True if the hard delete is successful, false otherwise.
     */
    public function hard_delete($table_name, $id)
    {
        $stmt = $this->con->prepare("DELETE FROM {$table_name} WHERE id = ?");
        $stmt->bind_param('s', $id);
        return $stmt->execute();
    }
}
