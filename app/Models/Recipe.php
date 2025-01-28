<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'type',
        'description',
    ];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipes_ingredients')->withPivot('quantity');
    }
}
