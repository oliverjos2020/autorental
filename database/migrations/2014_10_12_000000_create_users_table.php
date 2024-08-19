<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations. 
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('station_id')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('address')->nullable();
            $table->string('nin')->nullable();
            // $table->string('bank')->nullable();
            // $table->string('accountNumber')->nullable();
            // $table->string('accountName')->nullable();
            // $table->string('accountType')->nullable();
            // $table->string('passport')->nullable();
            $table->string('driverLicense')->nullable();
            $table->string('insurance')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
