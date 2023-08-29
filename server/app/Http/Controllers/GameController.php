<?php

namespace App\Http\Controllers;

use App\Http\Resources\Gamedev\GameResource;
use App\Models\Gamedev\Game;
use App\Models\Gamedev\GameGenre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


/**
 * TODO: JsonErrorResponse, SearchCriteria, ErrorCodes
 */
class GameController extends Controller
{
    public function index(
        Request $request
    )
    {
        $offset = 0;
        $limit = 10;

        $validator = Validator::make(
            $request->all(),
            [
                'limit' => 'integer|min:0|max:100',
                'offset' => 'integer|min:0',
                'genre_id' => 'integer|min:0',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => 1,
            ]);
        }

        $data = $validator->safe();

        if ($data->has('limit')) {
            $limit = $data['limit'];
        }

        if ($data->has('offset')) {
            $offset = $data['offset'];
        }

        if ($data->has('genre_id')) {
            $game_datas = GameGenre::query()
                ->with('game')
                ->with('game.studio')
                ->with('game.genres')
                ->where('genre_id', $data['genre_id'])
                ->offset($offset)
                ->limit($limit)
                ->get();

            $games = [];
            foreach ($game_datas as $game_data) {
                $games[] = $game_data->game;
            }
        } else {
            $games = Game::query()
                ->with(['studio', 'genres'])
                ->offset($offset)
                ->limit($limit)
                ->get();
        }

        return response()->json(GameResource::collection($games));
    }

    public function store(
        Request $request
    )
    {
        return static::updateOrCreate($request);
    }

    public function update(
        Request $request,
        Game $game
    )
    {
        return static::updateOrCreate($request, $game);
    }

    public function show(
        Game $game
    )
    {
        return new GameResource($game);
    }

    public function destroy(
        Game $game
    )
    {
        $game->delete();

        return response()->json('');
    }

    protected static function updateOrCreate(
        Request $request,
        Game $game = null
    )
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|min:1|max:255',
                'studio_id' => 'required|integer|min:0',
                'genre_ids' => 'array',
                'genre_ids.*' => 'integer|min:0',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => 1,
            ]);
        }

        $data = $validator->safe()->only(['name', 'studio_id', 'genre_ids']);

        if (!$game) {
            $game = new Game();
        }

        DB::beginTransaction();

        try {
            $game->fill($data);
            $game->save();
            $game->genres()->sync($data['genre_ids'] ?? []);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'error' => 1,
            ]);
        }

        DB::commit();

        return new GameResource($game);
    }
}
