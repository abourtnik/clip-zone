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
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('subscription_id')->nullable()->change();
            $table->dropForeign('transactions_subscription_id_foreign');
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
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('subscription_id')->change();
            $table->dropForeign('transactions_subscription_id_foreign');
            $table->foreign('subscription_id')->references('id')->on('premium_subscriptions');
        });
    }
};
