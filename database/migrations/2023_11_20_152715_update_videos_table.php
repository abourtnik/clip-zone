<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->timestamp('uploaded_at')->nullable();
            $table->string('file')->nullable()->change();
            $table->string('duration')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['uploaded_at']);
            $table->string('file')->nullable(false)->change();
            $table->string('duration')->nullable(false)->change();
        });
    }
};
