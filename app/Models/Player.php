<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'skill_level',
        'strength',
        'speed',
        'reaction_time',
    ];

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class);
    }

}
