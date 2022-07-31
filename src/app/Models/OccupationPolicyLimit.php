<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OccupationPolicyLimit extends Model
{
    use HasFactory;

    protected $table = 'policy_occupation_limit';

    public $incrementing = false;

    protected $fillable = [
        'percentage',
    ];
}