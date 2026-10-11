<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeUsersAccountNumberToString extends Migration
{
    /**
     * account_number was a bigint, which silently drops leading zeros
     * (e.g. 0014803762 became 14803762). Bank account numbers are 10-digit
     * strings, so store them as text and restore the lost zeros.
     */
    public function up()
    {
        DB::statement('ALTER TABLE users MODIFY account_number VARCHAR(10) NULL');
        DB::statement("UPDATE users SET account_number = LPAD(account_number, 10, '0') WHERE account_number IS NOT NULL AND account_number != '' AND CHAR_LENGTH(account_number) < 10");
    }

    public function down()
    {
        DB::statement('ALTER TABLE users MODIFY account_number BIGINT NULL');
    }
}
