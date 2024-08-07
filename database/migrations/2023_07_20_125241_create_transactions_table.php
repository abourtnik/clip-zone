<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_id')->index()->unique();
            $table->unsignedInteger('amount')->comment('amount in centimes');
            $table->unsignedInteger('tax')->comment('tax in centimes');;
            $table->unsignedInteger('fee')->comment('stripe fee for transaction');
            $table->timestamp('date');
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('country');
            $table->string('vat_id')->nullable();

            $table->foreign('subscription_id')->references('id')->on('premium_subscriptions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::dropIfExists('transactions');
    }
};
