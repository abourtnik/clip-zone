<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\PlaylistSort;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('playlists', function (Blueprint $table) {
            $table->after('status', function (Blueprint $table) {
                $table->integer('sort')->default(PlaylistSort::MANUAL);
            });
        });

        Schema::table('playlist_has_videos', function (Blueprint $table) {
            $table->timestamp('added_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('playlists', function (Blueprint $table) {
            $table->dropColumn(['sort']);
        });

        Schema::table('playlist_has_videos', function (Blueprint $table) {
            $table->dropColumn('added_at');
        });
    }
};
