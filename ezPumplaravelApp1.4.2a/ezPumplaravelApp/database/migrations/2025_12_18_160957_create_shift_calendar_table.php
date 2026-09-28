<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shift_calendars', function (Blueprint $table) {
            $table->id();
            $table->date('work_date');
            $table->dateTime('shift_start_time');
            $table->dateTime('shift_end_time')->nullable();
            $table->integer('total_duration')->nullable(); // minutes
            $table->timestamps();
        });
        Schema::create('shift_calendar_send', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calendar_id')->nullable();
            $table->date('work_date');
            $table->dateTime('shift_start_time');
            $table->dateTime('shift_end_time')->nullable();
            $table->integer('total_duration')->nullable(); // minutes
            $table->timestamps();
        });
        Schema::table('shift', function (Blueprint $table) {
            $table->unsignedBigInteger('calendar_id')->nullable()->after('id');
        });
        Schema::table('shiftsend', function (Blueprint $table) {
            $table->unsignedBigInteger('calendar_id')->nullable()->after('id');
        });
        Schema::table('tank_shifts', function (Blueprint $table) {
            $table->unsignedBigInteger('calendar_id')->nullable()->after('id');
        });

        Schema::table('tank_shifts_send', function (Blueprint $table) {
            $table->unsignedBigInteger('calendar_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_calendar');
    }
};
