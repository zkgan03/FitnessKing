<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id('timetable_id');  
            $table->foreignId('class_id')->constrained('gym_classes', 'class_id')->onDelete('cascade');  
            $table->integer('day'); 
            $table->string('timeslot'); 
            $table->boolean('is_assigned')->default(false);  
            $table->timestamps();
    
            $table->unique(['day', 'timeslot'], 'unique_day_timeslot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
