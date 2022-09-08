<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goal_nutritions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('goal_id')->nullable();
            $table->double('breakfast_fat')->default(0);
            $table->double('breakfast_carbs')->default(0);
            $table->double('breakfast_protein')->default(0);
            $table->double('lunch_fat')->default(0);
            $table->double('lunch_carbs')->default(0);
            $table->double('lunch_protein')->default(0);
            $table->double('dinner_fat')->default(0);
            $table->double('dinner_carbs')->default(0);
            $table->double('dinner_protein')->default(0);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('goal_nutritions');
    }
};
