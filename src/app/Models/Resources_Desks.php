<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Resources_Desks extends Pivot
{
    use HasFactory;

    protected $table = 'resources_desks';
    
    protected $primaryKey = 'resource_desk_id';

    protected $fillable = [
        'resource_id',
        'desk_id',
    ];

    public function resources() {
        return $this->belongsTo(Resources::class, 'resource_id', 'resource_id');
    }
}
