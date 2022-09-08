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
        Schema::create('goal_has_meals', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('goal_id');
            $table->integer('day')->nullable();
            $table->integer('meal_time')->nullable();
            $table->integer('meal_item_id')->nullable();
            $table->double('value')->default(0);
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
        Schema::dropIfExists('goal_has_meals');
    }
};
