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
        Schema::create('class_packages', function (Blueprint $table) {
            $table->id('package_id')->primary();
            $table->string('package_name');
            $table->string('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->double('price');
            $table->integer('max_capacity');
            $table->string('package_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_packages');
    }
};
