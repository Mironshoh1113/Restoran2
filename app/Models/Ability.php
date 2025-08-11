<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id', 'name', 'label'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'ability_role');
    }
}
