<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->integer('costomer');
            $table->integer('consultant');
            $table->string('address');
            $table->string('code')->comment('Post Code');
            $table->dateTime('arrive_time');
            $table->dateTime('turnaround_time');
            $table->integer('distance');
            $table->enum('status', ['success', 'waiting', 'cancel'])->default('waiting');
            $table->softDeletes();
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
        Schema::dropIfExists('calendars');
    }
}
