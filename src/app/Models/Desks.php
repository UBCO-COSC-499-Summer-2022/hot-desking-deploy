<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Desks extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pos_x',
        'pos_y',
        'is_closed',
    ];

    public function room() {
        return $this->belongsTo(Rooms::class);
    }

    public function users() {
        return $this->belongsToMany(Users::class, 'bookings', 'desk_id', 'user_id')->withPivot('id', 'book_time_start', 'book_time_end', 'desk_id');
    }

    public function resources() {
        return $this->belongsToMany(Resources::class, 'resources_desks', 'desk_id', 'resource_id')->withPivot('resource_desk_id');
    }
}
