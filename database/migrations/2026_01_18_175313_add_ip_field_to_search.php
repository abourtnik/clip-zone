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
        Schema::table('searches', function (Blueprint $table) {
            $table->after('query', function (Blueprint $table) {
                $table->string('ip')->nullable();
                $table->string('lang')->nullable();
                $table->unsignedSmallInteger('results')->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('searches', function (Blueprint $table) {
            $table->dropColumn(['ip', 'lang', 'results']);
        });
    }
};
