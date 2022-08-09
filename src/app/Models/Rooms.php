<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class Rooms extends Model
{
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $cascadeDeletes = ['desks'];
    protected $dates = ['deleted_at'];


    protected $fillable = [
        'name',
        'occupancy',
        'rows',
        'cols',
        'is_closed',
    ];

    public function floor() {
        return $this->belongsTo(Floors::class);
    }

    public function desks() {
        return $this->hasMany(Desks::class, 'room_id');
    }

    public function roles() {
        return $this->belongsToMany(Roles::class, 'role_room', 'room_id', 'role_id');
    }

    public function resources() {
        return $this->belongsToMany(Resources::class, 'resources_rooms', 'room_id', 'resource_id')->withPivot('resource_room_id', 'description');
    }
}
