<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleRoom extends Pivot
{
    use HasFactory;
    
    protected $table = 'role_room';

    public $incrementing = false;
}