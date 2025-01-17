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
        Schema::create('coaches', function (Blueprint $table) {
            $table->id('coach_id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('password');
            $table->string('email')->unique();
            $table->char('gender', 1);
            $table->string('phone_number');
            $table->date('birth_date');
            $table->timestamp('creation_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('profile_pic')->nullable();
            $table->string('description');
            $table->char('coach_status', 1);

            // Add any additional indexes or foreign keys here if needed
        });

        // DB::statement("ALTER TABLE coaches ADD profilePic MEDIUMBLOB");
        // DB::statement("ALTER TABLE coaches ADD certificate MEDIUMBLOB");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coaches');
    }
};
