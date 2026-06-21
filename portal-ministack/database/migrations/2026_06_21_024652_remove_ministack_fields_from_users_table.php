<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ministack_account_id', 'access_key_id', 'secret_access_key', 'bucket_name']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ministack_account_id', 12)->nullable()->after('id');
            $table->string('access_key_id')->nullable();
            $table->text('secret_access_key')->nullable();
            $table->string('bucket_name')->nullable();
        });
    }
};
