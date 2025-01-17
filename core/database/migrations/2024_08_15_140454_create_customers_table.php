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
        Schema::create('customers', function (Blueprint $table) {
            $table->id('customer_id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('password');
            $table->string('email')->unique();
            $table->char('gender', 1);
            $table->string('phone_number');
            $table->date('birth_date');
            $table->timestamp('creation_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('profile_pic')->nullable();
        });      

        // DB::statement("ALTER TABLE customers ADD profilePic MEDIUMBLOB");
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
