<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $table = 'holidays';

    
    protected $fillable = [
        'day',
        'date',
        'date_formatted',
        'month',
        'name',
        'description',
        'is_holiday',
        'type',
        'type_id',
        'region',
    ];
}
