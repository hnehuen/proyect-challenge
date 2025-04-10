<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'status',
        'winner_id',
    ];

    public function players()
    {
        return $this->belongsToMany(Player::class, 'player_tournament');
    }

    public function winner()
    {
        return $this->belongsTo(Player::class, 'winner_id');
    }
}
