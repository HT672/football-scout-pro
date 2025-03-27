<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->string('nationality');
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('position_id')->nullable()->constrained()->onDelete('set null');
            $table->string('photo')->nullable();
            $table->integer('height')->nullable()->comment('Height in cm');
            $table->integer('weight')->nullable()->comment('Weight in kg');
            $table->string('preferred_foot')->default('right');
            $table->integer('jersey_number')->nullable();
            $table->text('bio')->nullable();
            $table->decimal('market_value', 10, 2)->nullable()->comment('Value in millions');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('players');
    }
}