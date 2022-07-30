<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floors extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'floor_num',
        'is_closed',
    ];

    public function Building() {
        return $this->belongsTo(Buildings::class);
    }

    public function Rooms() {
        return $this->hasMany(Rooms::class);
    }
}
