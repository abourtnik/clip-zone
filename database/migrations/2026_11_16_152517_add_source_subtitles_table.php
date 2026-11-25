<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subtitles', function (Blueprint $table) {
            $table->after('status', function (Blueprint $table) {
                $table->boolean('is_enabled')->default(true);
                $table->string('provider_id')->nullable();
            });

            $table->string('language', 3)->nullable()->change();
            $table->string('file')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subtitles', function (Blueprint $table) {
            $table->dropColumn(['is_enabled', 'provider_id']);
        });
    }
};
