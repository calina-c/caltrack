<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_item_id',
        'multiplier',
        'ate_at',
        'description',
        'direct_kcal',
        'direct_protein',
        'direct_name',
    ];

    public function foodItem()
    {
        return $this->belongsTo(FoodItem::class);
    }
}
