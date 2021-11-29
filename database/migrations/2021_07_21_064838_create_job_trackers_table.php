<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_trackers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('model_type');
            $table->uuid('model_id');
            $table->string('job_class');

            $table->tinyInteger('status')->default(1);
            $table->text('message')->nullable();
            $table->json('return_response')->nullable();
            $table->timestamp('response_received_at')->nullable();

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
        Schema::dropIfExists('job_trackers');
    }
}
