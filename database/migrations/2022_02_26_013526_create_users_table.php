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
        Schema::create('users_table', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->integer('cpf');
            $table->integer('phone');
            $table->string('email', 100);
            $table->string('address', 150);
            $table->text('note');
            $table->timestamps();

            // protected $fillable = ['name', 'cpf', 'phone', 'email', 'address', 'note'];
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
