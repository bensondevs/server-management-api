<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_addons', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('addon_name');
            $table->string('addon_type');

            $table->integer('quantity')->default(1);
            $table->char('unit')->default('pcs');

            $table->text('description')->nullable();

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
        Schema::dropIfExists('service_addons');
    }
}
