<?php

namespace App\Models;

use App\Helper\DatabaseHelper;

class User extends BaseModel
{
    protected $table = 'user';
    protected $validation;
    protected $requiredFields = [
        'username',
        "password",
        "role_id"
    ];

    public function __construct(DatabaseHelper $db)
    {
        parent::__construct($db);
    }
}
