<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'has_printer',
        'has_projector',
        'room_image',
        'is_closed',
    ];

    public function Floor() {
        return $this->belongsTo(Floors::class);
    }

    public function Desks() {
        return $this->hasMany(Desks::class);
    }
}
