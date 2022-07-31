<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resources extends Model
{
    use HasFactory;

    protected $table = 'resources';
    
    protected $primaryKey = 'resource_id';

    protected $fillable = [
        'resource_type',
        'icon',
        'colour',
    ];
}
