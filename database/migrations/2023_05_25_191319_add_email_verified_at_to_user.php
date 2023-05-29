<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddEmailVerifiedAtToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->datetime('email_verified_at')->nullable();
        });

        // Mark default admin user as verified
        DB::table('user')
            ->where('id_user', 1)
            ->update(['email_verified_at' => '1970-01-01']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
        });
    }
}
