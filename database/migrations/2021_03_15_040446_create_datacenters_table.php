<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatacentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datacenters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('region_id');
            $table->foreign('region_id')
                ->references('id')
                ->on('regions')
                ->onDelete('CASCADE');

            $table->string('datacenter_name');
            $table->string('client_datacenter_name');
            $table->string('location');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('datacenters');
    }
}
