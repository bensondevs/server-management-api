<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\ServicePlan\ServicePlanStatus as Status;

class CreateServicePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('plan_name')->unique();
            $table->string('plan_code')->unique();
            $table->longText('description')->nullable();

            $table->tinyInteger('status')->default(Status::Active);

            $table->integer('duration_days')->default(1);

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
        Schema::dropIfExists('service_plans');
    }
}
