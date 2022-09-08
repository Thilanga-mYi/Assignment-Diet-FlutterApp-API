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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->string('ref')->nullable();
            $table->integer('user_id');
            $table->integer('goal_type_id');
            $table->double('height')->nullable();
            $table->double('weight')->nullable();
            $table->double('goal_weight')->nullable();
            $table->integer('body_type')->nullable();
            $table->integer('activity_level')->nullable();
            $table->tinyInteger('vegi_type')->nullable();
            $table->double('drink_water')->nullable();
            $table->double('diabetes')->nullable();
            $table->double('cholesterol')->nullable();
            $table->double('fatty_liver')->nullable();
            $table->double('bmr')->nullable();
            $table->double('tdee')->nullable();
            $table->tinyInteger('payment_status')->default(2);
            $table->tinyInteger('status')->default(2);
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
        Schema::dropIfExists('goals');
    }
};
