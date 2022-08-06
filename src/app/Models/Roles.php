<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $primaryKey = 'role_id';

    protected $fillable = [
        'role',
        'num_monthly_bookings',
        'max_booking_window',
        'max_booking_duration',
    ];

    public function Users() {
        return $this->hasMany(User::class);
    }

    public function rooms() {
        return $this->belongsToMany(Rooms::class, 'role_room', 'role_id', 'room_id');
    }
} 
