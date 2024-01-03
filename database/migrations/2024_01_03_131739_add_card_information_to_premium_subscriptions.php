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
    public function up() :void
    {
        Schema::table('premium_subscriptions', function (Blueprint $table) {
            $table->after('trial_ends_at', function (Blueprint $table) {
                $table->string('card_last4')->after('trials_end_at');
                $table->date('card_expired_at')->after('trials_end_at');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::table('premium_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['card_last4', 'card_expired_at']);
        });
    }
};
