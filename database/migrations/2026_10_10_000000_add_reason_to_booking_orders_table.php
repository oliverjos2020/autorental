<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReasonToBookingOrdersTable extends Migration
{
    /**
     * Holds the reason a booking was declined or a trip was cut short.
     * Guarded because BookingAPIController::updateTrip already writes to it.
     */
    public function up()
    {
        if (!Schema::hasColumn('booking_orders', 'reason')) {
            Schema::table('booking_orders', function (Blueprint $table) {
                $table->text('reason')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('booking_orders', 'reason')) {
            Schema::table('booking_orders', function (Blueprint $table) {
                $table->dropColumn('reason');
            });
        }
    }
}
