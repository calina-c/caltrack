<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'unit_name',
        'unit_base_quantity',
        'kcal',
        'protein',
    ];

    public function entries()
    {
        return $this->hasMany(FoodEntry::class);
    }

    public function thumbs()
    {
        return $this->hasOne(Thumbs::class);
    }
}
