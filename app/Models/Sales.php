<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $sales = 'sales';

    protected $fillable = [
        'productname',
        'price',
        'quantity',
        'total',
        'date',
    ];
}
