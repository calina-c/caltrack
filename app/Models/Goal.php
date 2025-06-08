<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $table = 'goals';

    protected $fillable = [
        'date',
        'qty',
        'goal_type_id',
    ];

    protected $casts = [
        'qty' => 'integer',
        'date' => 'date',
    ];

    public function goalType()
    {
        return $this->belongsTo(GoalType::class);
    }
}
