<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalType extends Model
{
    use HasFactory;

    protected $table = 'goal_types';

    protected $fillable = [
        'name',
        'target_qty',
        'is_current',
    ];

    protected $casts = [
        'target_qty' => 'integer',
        'current' => 'boolean',
    ];

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }
}
