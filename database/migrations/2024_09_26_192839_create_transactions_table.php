<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_order_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_id');
            $table->string('transaction_desc');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('amount');
            $table->string('response_code');
            $table->string('response_message');
            $table->text('raw_json');
            $table->char('status');
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
        Schema::dropIfExists('transactions');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::table('transactions', function (Blueprint $table) {
            // $table->dropColumn('user_id');
            $table->dropColumn('product_id');
        });
    }
}
