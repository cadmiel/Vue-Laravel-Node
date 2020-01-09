<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('username',100);
            $table->string('password');
            $table->string('provider_user_id',100)->nullable();
            $table->string('provider',10)->nullable();
            $table->text('first_password');
            $table->string('acting_level',11)->nullable();
            $table->boolean('blocked')->default(false);
            $table->boolean('active')->default(true);
            $table->boolean('super_admin')->default(false);
            $table->boolean('change_password')->default(true);
            $table->timestamp('password_changed_at')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
