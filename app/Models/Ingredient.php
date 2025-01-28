<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'type',
        'description',
    ];

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipes_ingredients')->withPivot('quantity');
    }
}
