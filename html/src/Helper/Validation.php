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

    public function isPhoneSingapore($phone)
    {
        // The phone number must be a string and start with "+65" followed by 8 digits
        return is_string($phone) && preg_match('/^\+65\d{8}$/', $phone);
    }

    public function isPostalValid($postalCode)
    {
        // The postal code must be a string of 6 digits
        return is_string($postalCode) && preg_match('/^\d{6}$/', $postalCode);
    }
}
