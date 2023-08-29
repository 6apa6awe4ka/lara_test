<?php

namespace App\Models\Gamedev;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GameGenre extends Model
{
    use HasFactory;

    protected $table = 'games_genres';
    public $timestamps = false;

    public function game(): HasOne
    {
        return $this->hasOne(Game::class, 'id', 'game_id');
    }
}
