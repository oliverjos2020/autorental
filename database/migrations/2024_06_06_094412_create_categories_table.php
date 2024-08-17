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
            ['category' => 'Hire', 'slug' => Str::of(Str::lower('Hire'))->slug('-'), 'created_at' => Carbon::now()],
            ['category' => 'Booking', 'slug' => Str::of(Str::lower('Booking'))->slug('-'), 'created_at' => Carbon::now()],
            ['category' => 'Entertainment', 'slug' => Str::of(Str::lower('Entertainment'))->slug('-'), 'created_at' => Carbon::now()]
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
        Schema::dropIfExists('categories');
    }
}
