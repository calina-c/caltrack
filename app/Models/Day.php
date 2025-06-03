<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;
    protected $table_name = 'days';

    protected $fillable = [
        'date',
        'done',
        'rating',
    ];

    protected $casts = [
        'date' => 'date',
        'done' => 'boolean',
        'rating' => 'integer',
    ];
}
