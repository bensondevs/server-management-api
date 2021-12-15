<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\Subscription\SubscriptionStatus as Status;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

            $table->uuidMorphs('subscribeable'); // Service plan or addon
            $table->nullableUuidMorphs('subscriber'); // Container or any services

            $table->tinyInteger('status')->default(Status::Active);

            $table->datetime('start');
            $table->datetime('end');

            $table->timestamps();
            $table->timestamp('expired_at');
            $table->timestamp('terminated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
