<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\ContainerProperty\ContainerPropertyType;

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

            $table->tinyInteger('property_type')
                ->default(ContainerPropertyType::DiskSize);
            $table->integer('property_value')->default(0);
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
