<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPasswordHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_password_history', function (Blueprint $table) {
            $table->id();
            $table->string('pass_from');
            $table->string('pass_to');
            $table->foreignId('agent_id');
            $table->string('ip');

            $table->morphs('user');
            $table->timestamps();

            // Relationships
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_password_history');
    }
}
