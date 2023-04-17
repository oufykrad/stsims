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
        Schema::create('qualifiers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('spas_id')->unique()->nullable();
            $table->string('school_code');
            $table->string('course_id');
            $table->year('year');
            $table->boolean('is_undergrad');
            $table->boolean('is_waiting')->default(0);
            $table->boolean('is_referral')->default(0);
            $table->boolean('is_qualified')->nullable();
            $table->tinyInteger('program_id')->unsigned()->index();
            $table->foreign('program_id')->references('id')->on('list_programs')->onDelete('cascade');
            $table->bigInteger('profile_id')->unsigned()->index();
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
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
        Schema::dropIfExists('qualifiers');
    }
};
