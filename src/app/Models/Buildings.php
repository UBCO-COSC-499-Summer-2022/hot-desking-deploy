<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buildings extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'is_closed',
    ];

    public function Campus() {
        return $this->belongsTo(Campuses::class);
    }

    public function Floors() {
        return $this->hasMany(Floors::class);
    }
}
