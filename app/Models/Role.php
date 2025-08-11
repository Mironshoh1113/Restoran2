<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id', 'name', 'label'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function abilities()
    {
        return $this->belongsToMany(Ability::class, 'ability_role');
    }
}
