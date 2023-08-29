<?php

namespace App\Models\Gamedev;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'studio_id',
    ];

    public $timestamps = false;

    public function studio(): HasOne
    {
        return $this->hasOne(Studio::class, 'id', 'studio_id');
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'games_genres', 'game_id', 'genre_id');
    }
}
