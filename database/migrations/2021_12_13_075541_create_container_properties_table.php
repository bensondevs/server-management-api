<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\ContainerProperty\{
    ContainerPropertyType as Type,
    ContainerPropertyChangeStatus as ChangeStatus
};

class CreateContainerPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('container_properties', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('container_id');
            $table->foreign('container_id')
                ->references('id')
                ->on('containers')
                ->onDelete('CASCADE');

            $table->tinyInteger('property_type')->default(Type::DiskSize);
            $table->string('property_value')->nullable();

            $table->tinyInteger('change_status')->default(ChangeStatus::Uncreated);

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
        Schema::dropIfExists('container_properties');
    }
}
