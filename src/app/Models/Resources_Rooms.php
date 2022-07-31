<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Resources_Rooms extends Pivot
{
    use HasFactory;

    protected $table = 'resources_rooms';
    
    protected $primaryKey = 'resource_room_id';

    protected $fillable = [
        'resource_id',
        'room_id',
        'description',
    ];
}
