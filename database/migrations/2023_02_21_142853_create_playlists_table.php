<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\User;
use App\Models\Video;
use App\Models\Playlist;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() : void
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();
        });

        Schema::create('playlist_has_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Playlist::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Video::class)->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('position')->default(0);
        });

        Schema::create('favorites_playlist', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Playlist::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->timestamp('added_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorites_playlist');
        Schema::dropIfExists('playlist_has_videos');
        Schema::dropIfExists('playlists');
    }
};
