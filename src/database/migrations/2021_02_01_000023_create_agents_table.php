<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();

            $table->text('user_agent');
            $table->string('type')->nullable(); // broswer, Feed Reader etc...

            $table->foreignId('device_id')->nullable();
            $table->foreignId('operation_system_id')->nullable();
            $table->foreignId('browser_id')->nullable();
            
            $table->boolean('is_bot')->default(false);
            $table->foreignId('bot_id')->nullable();
            
            // Relationships
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('set null');
            $table->foreign('operation_system_id')->references('id')->on('operation_systems')->onDelete('set null');
            $table->foreign('browser_id')->references('id')->on('browsers')->onDelete('set null');
            $table->foreign('bot_id')->references('id')->on('bots')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agents');
    }
}
