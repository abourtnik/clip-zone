<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Video;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('confirmation_token')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();
            $table->boolean('is_admin')->nullable();
            $table->rememberToken();
            $table->string('avatar')->nullable();
            $table->string('banner')->nullable();
            $table->text('description')->nullable();
            $table->string('country', 3)->nullable();
            $table->string('website')->nullable();
            $table->boolean('show_subscribers')->default(true);
            $table->timestamp('banned_at')->nullable();
            $table->foreignIdFor(Video::class, 'pinned_video_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
