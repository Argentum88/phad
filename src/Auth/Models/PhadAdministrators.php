<?php

namespace Argentum88\Phad\Auth\Models;

use Phalcon\Mvc\Model;

class PhadAdministrators extends Model
{
    public $id;
    public $name;
    public $password;
    public $created_at;
    public $updated_at;
}
