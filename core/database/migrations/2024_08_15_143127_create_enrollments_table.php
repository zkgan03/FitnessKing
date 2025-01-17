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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id('enrollment_id')->primary();
            $table->dateTime('enroll_datetime')->default(now());
            $table->foreignId('package_id')->constrained('class_packages', 'package_id');
            $table->foreignId('payment_id')->constrained('payments', 'payment_id');
            $table->foreignId('customer_id')->constrained('customers', 'customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
