<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;


class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('slug')->unique();
            $table->timestamps();
        });
        $this->insertDefaultData();

    }

      private function insertDefaultData()
    {
        $data = [
            ['role' => 'Admin', 'slug' => Str::of(Str::lower('Admin'))->slug('-')],
            ['role' => 'Station Admin', 'slug' => Str::of(Str::lower('Station Admin'))->slug('-')],
            ['role' => 'Driver', 'slug' => Str::of(Str::lower('Driver'))->slug('-')],
            ['role' => 'Support', 'slug' => Str::of(Str::lower('Support'))->slug('-')],
            ['role' => 'Mobile App User', 'slug' => Str::of(Str::lower('Mobile App User'))->slug('-')]
            // Add more default data as needed
        ];

        DB::table('roles')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
