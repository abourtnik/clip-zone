<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\User;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file');
            $table->string('mimetype');
            $table->string('thumbnail');
            $table->unsignedMediumInteger('duration');
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Category::class)->nullable()->constrained();
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('language', 3)->nullable();
            $table->timestamp('publication_date')->useCurrent();
            $table->boolean('allow_comments')->default(true);
            $table->string('default_comments_sort')->default('top');
            $table->boolean('show_likes')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
};
