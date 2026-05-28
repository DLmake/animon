<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// TASK 1-E: Modify the existing users table
// The users table already exists (Laravel created it).
// This migration ADDS columns to it — for Google login support.
// Schema::table() modifies an existing table (vs Schema::create() which makes a new one).

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // TODO: Add these three columns

            // google_id : string, nullable, unique — Google's ID for this user
            //             ->after('email') puts it after the email column
            //             ->nullable() because email/password users won't have one

            // avatar_url: string(500), nullable, ->after('google_id')
            //             Profile picture URL (from Google, or uploaded manually)

            // Make password optional — Google users won't have one:
            // $table->string('password')->nullable()->change();
            // Note: ->change() modifies an existing column instead of adding a new one
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'avatar_url']);
            $table->string('password')->nullable(false)->change();
        });
    }
};
