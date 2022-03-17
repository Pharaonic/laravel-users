<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_agents', function (Blueprint $table) {
            $table->id();
            $table->morphs('user');
            $table->string('signature');
            $table->foreignId('agent_id');
            $table->unique(['user_id', 'user_type', 'signature', 'agent_id']);

            $table->string('ip');
            $table->string('fcm_token')->nullable();
            $table->timestamp('last_action_at');
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
        Schema::dropIfExists('user_agents');
    }
}
