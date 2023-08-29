<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(
            'CREATE TABLE studios (
                id SERIAL PRIMARY KEY,
                name varchar(255) NOT NULL CHECK (name <> \'\')
            )'
        );

        DB::statement(
            'CREATE TABLE genres (
                id SERIAL PRIMARY KEY,
                name varchar(255) UNIQUE NOT NULL CHECK (name <> \'\')
            )'
        );

        DB::statement(
            'CREATE TABLE games (
                id SERIAL PRIMARY KEY,
                studio_id integer NOT NULL references studios(id),
                name varchar(255) NOT NULL CHECK (name <> \'\'),
                UNIQUE (studio_id, name)
            )'
        );

        DB::statement(
            'CREATE TABLE games_genres (
                game_id  integer NOT NULL references games(id) ON DELETE CASCADE,
                genre_id  integer NOT NULL references genres(id) ON DELETE CASCADE,
                UNIQUE (genre_id, game_id)
            )'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::drop(
            'games_genres'
        );

        Schema::drop(
            'games'
        );

        Schema::drop(
            'studios'
        );

        Schema::drop(
            'genres'
        );
    }
};
