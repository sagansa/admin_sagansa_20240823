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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('salary_rate_id')->constrained();
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('years_of_service');
            $table->integer('total_work_days');
            $table->integer('total_hours');
            $table->integer('rate_per_hour');
            $table->integer('base_salary');
            $table->json('allowances')->nullable();
            $table->json('deductions')->nullable();
            $table->integer('total_salary');
            $table->string('status')->default('1');
            $table->foreignId('approved_by_id')->nullable()->constrained('users');
            $table->datetime('approved_at')->nullable();
            $table->datetime('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Memastikan tidak ada duplikasi gaji untuk periode yang sama
            $table->unique(['user_id', 'period_start', 'period_end']);
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
