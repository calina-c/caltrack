<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thumbs extends Model
{
    use HasFactory;

    protected $table = 'thumbs';

    protected $fillable = [
        'food_item_id',
        'food_entry_id',
        'type'
    ];

    public function foodItem()
    {
        return $this->belongsTo(FoodItem::class);
    }

    public function foodEntry()
    {
        return $this->belongsTo(FoodEntry::class);
    }
}
