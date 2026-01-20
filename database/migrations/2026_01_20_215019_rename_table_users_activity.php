<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('users_activity', 'activities');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('activities', 'users_activity');
    }
};
