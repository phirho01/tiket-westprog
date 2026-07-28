<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_account')) {
            Schema::create('user_account', function (Blueprint $table) {
                $table->increments('id_user');
                $table->string('nama');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('no_hp')->nullable();
                $table->string('role')->default('wisatawan');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_account');
    }
};
