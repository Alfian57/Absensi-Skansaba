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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nisn')->unique();
            $table->string('nis')->unique();
            $table->string('name');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->text('address');
            $table->unsignedBigInteger('grade_id');
            $table->year('entry_year');
            $table->string('profile_pic')->nullable()->default(null);
            $table->string('password');
            $table->boolean('already_login')->default(false);

            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('cascade');

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
        Schema::dropIfExists('students');
    }
};
