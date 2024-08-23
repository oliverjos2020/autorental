<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('slug')->unique();
            $table->timestamps();
        });
        $this->insertDefaultData();
    }

    private function insertDefaultData()
    {
        $data = [
            ['category' => 'Sports Car', 'slug' => Str::of(Str::lower('Sports Car'))->slug('-'), 'created_at' => Carbon::now()],
            ['category' => 'Truck', 'slug' => Str::of(Str::lower('Truck'))->slug('-'), 'created_at' => Carbon::now()],
            ['category' => 'Convertible', 'slug' => Str::of(Str::lower('Convertible'))->slug('-'), 'created_at' => Carbon::now()],
            ['category' => 'SUV', 'slug' => Str::of(Str::lower('SUV'))->slug('-'), 'created_at' => Carbon::now()],
            ['category' => 'Buses', 'slug' => Str::of(Str::lower('Buses'))->slug('-'), 'created_at' => Carbon::now()],
            ['category' => 'Sedan', 'slug' => Str::of(Str::lower('Sedan'))->slug('-'), 'created_at' => Carbon::now()]
            // Add more default data as needed
        ];

        DB::table('categories')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('categories');
    }
}
