<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('station_id')->constrained()->cascadeOnDelete();
            $table->string('vehicleMake')->nullable();
            $table->string('vehicleYear')->nullable();
            $table->string('vehicleModel')->nullable();
            $table->string('transmission')->nullable();
            $table->string('doors')->nullable();
            $table->enum('airCondition', ['yes', 'no'])->nullable();
            $table->string('seats')->nullable();
            $table->foreignId('price_setup_id')->constrained()->cascadeOnDelete()->nullable();
            $table->char('status', 1)->default('0')->nullable();
            $table->char('on_trip', 1)->default('0')->nullable();
            $table->text('moreInfo')->nullable();
            $table->enum('keylessEntry', ['yes', 'no'])->nullable();
            $table->enum('musicPlayer', ['yes', 'no'])->nullable();
            $table->enum('airBags', ['yes', 'no'])->nullable();
            $table->string('doors')->nullable();
            $table->string('fuelCapacity')->nullable();
            $table->string('maxSpeed')->nullable();
            $table->string('maxPower')->nullable();
            $table->string('motor')->nullable();
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('vehicles');
        Schema::table('vehicles', function (Blueprint $table) {
            // $table->dropColumn('user_id');
            // $table->dropColumn('product_id');
        });

    }
}
