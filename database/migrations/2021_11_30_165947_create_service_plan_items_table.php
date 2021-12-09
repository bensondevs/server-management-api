<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicePlanItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_plan_items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('service_plan_id');
            $table->foreign('service_plan_id')
                ->references('id')
                ->on('service_plans')
                ->onDelete('CASCADE');

            $table->tinyInteger('property_type')->default(1);
            $table->integer('property_unit_quantity')->default(1);
            $table->longText('description')->nullable();

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
        Schema::dropIfExists('service_plan_items');
    }
}
