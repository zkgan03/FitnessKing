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
        Schema::create('gym_classes', function (Blueprint $table) {
            $table->id('class_id')->primary();
            $table->string('class_name');
            $table->string('class_image');
            $table->string('description');
            $table->string('class_type');
            $table->string('classroom');
            $table->date('class_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('coach_id')->constrained('coaches', 'coach_id');
            $table->foreignId('package_id')->constrained('class_packages', 'package_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_classes');
    }
};
