<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;


class Campuses extends Model
{
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['buildings'];
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

    public function buildings() {
        return $this->hasMany(Buildings::class, 'campus_id');
    }

    public function faculties(){
        return $this->hasMany(faculty::class, 'id','campus_id');
    }
}
