<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $table = 'exercises';

    protected $fillable = [
        'date',
        'exercise_type_id',
        'label',
    ];

    protected $casts = [
        'date' => 'date',
        'exercise_type_id' => 'integer',
        'label' => 'string',
    ];

    public function exerciseType()
    {
        return $this->belongsTo(ExerciseType::class);
    }
}
