<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsRegTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools_reg', function (Blueprint $table) {
            $table->increments('id');
            $table->string('schools_name');
            $table->string('schools_code');
            $table->string('schools_establish_date');
            $table->string('schools_adress');
            $table->string('schools_password');
            $table->string('schools_email');
            $table->string('schools_phone');
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
        Schema::dropIfExists('schools_reg');
    }
}
