<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desks extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pos_x',
        'pos_y',
        'has_outlet',
        'is_closed',
    ];

    public function Room() {
        return $this->belongsTo(Rooms::class);
    }

    public function Desks() {
        return $this->belongsToMany('App\Models\Desks','bookings','desk_id','user_id')->withPivot('name');
    }
}
