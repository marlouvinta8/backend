<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservations extends Model
{
    use HasFactory;

    protected $reservations = 'reservations';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'reserve_date',
        'service',
    ];
}
