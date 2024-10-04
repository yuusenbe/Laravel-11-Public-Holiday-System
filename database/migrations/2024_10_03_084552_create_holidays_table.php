<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('day'); // Day of the week
            $table->date('date'); // Date of the holiday
            $table->string('date_formatted'); // Formatted date
            $table->string('month'); // Month name
            $table->string('name'); // Name of the holiday
            $table->string('description')->nullable(); // Optional description
            $table->boolean('is_holiday'); // Whether it's a holiday
            $table->string('type'); // Type of holiday
            $table->integer('type_id'); // ID for holiday type
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('holidays');
    }
}
