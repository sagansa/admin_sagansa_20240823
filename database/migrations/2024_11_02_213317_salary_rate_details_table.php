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
        Schema::create('salary_rate_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_rate_id')->constrained();
            $table->integer('years_of_service');
            $table->integer('rate_per_hour');
            $table->timestamps();

            // Memastikan tidak ada duplikasi masa kerja dalam satu rate
            $table->unique(['salary_rate_id', 'years_of_service']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
