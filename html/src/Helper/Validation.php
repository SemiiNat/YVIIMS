<?php

namespace Helper;

namespace App\Helper;

class Validation
{
    protected $db;

    public function __construct(DatabaseHelper $db)
    {
        $this->db = $db;
    }

    public function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function isUnique($table, $field, $value)
    {
        $existingRecord = $this->db->getOne("SELECT * FROM {$table} WHERE {$field} = ?", [$value]);
        return $existingRecord === null;
    }
}
