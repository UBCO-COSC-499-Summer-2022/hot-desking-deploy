<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;


class Buildings extends Model
{
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['floors'];
    protected $dates = ['deleted_at'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'is_closed',
    ];

    public function campus() {
        return $this->belongsTo(Campuses::class);
    }

    public function floors() {
        return $this->hasMany(Floors::class, 'building_id');
    }

}
