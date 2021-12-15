<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainerAdditionalPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('container_additional_properties', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('container_id');
            $table->foreign('container_id')
                ->references('id')
                ->on('containers')
                ->onDelete('CASCADE');

            $table->uuid('container_property_id');
            $table->foreign('container_property_id')
                ->references('id')
                ->on('container_properties')
                ->onDelete('CASCADE');

            $table->string('additional_value')->nullable();

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
        Schema::dropIfExists('container_additional_properties');
    }
}
