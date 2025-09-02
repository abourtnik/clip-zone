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
        Schema::table('videos', function (Blueprint $table) {
           $table->renameColumn('scheduled_date', 'scheduled_at');
           $table->renameColumn('publication_date', 'published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->renameColumn('scheduled_at', 'scheduled_date');
            $table->renameColumn('published_at', 'publication_date');
        });
    }
};
