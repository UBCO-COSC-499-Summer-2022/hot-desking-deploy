<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Bookings extends Pivot
{
    use HasFactory;

    protected $table = "bookings";

    protected $primaryKey = "id";
    public $incrementing = true;

    protected $fillable = [
        'book_time_start',
        'book_time_end',
    ];
}