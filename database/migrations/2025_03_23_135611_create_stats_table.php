<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatsTable extends Migration
{
    public function up()
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('match_id')->nullable()->constrained('football_matches')->onDelete('cascade');
            $table->string('season');
            $table->integer('minutes_played')->default(0);
            $table->integer('goals')->default(0);
            $table->integer('assists')->default(0);
            $table->integer('yellow_cards')->default(0);
            $table->integer('red_cards')->default(0);
            $table->integer('passes')->default(0);
            $table->decimal('pass_accuracy', 5, 2)->nullable();
            $table->integer('shots')->default(0);
            $table->integer('shots_on_target')->default(0);
            $table->integer('tackles')->default(0);
            $table->integer('interceptions')->default(0);
            $table->integer('saves')->default(0)->comment('For goalkeepers');
            $table->integer('clean_sheets')->default(0)->comment('For goalkeepers');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stats');
    }
}