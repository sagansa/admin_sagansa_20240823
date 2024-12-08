<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\SalaryRate;
use App\Models\SalaryRateDetail;
use Carbon\Carbon;

class UpdateEmployeeSalaryRates extends Command
{
    protected $signature = 'employee:update-salary-rates';
    protected $description = 'Update salary_rate_per_hour for all employees based on salary_rates and salary_rate_details';

    public function handle()
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            $yearsOfService = $employee->calculateYearsOfService();
            $currentYear = Carbon::now()->year;

            // Find the salary rate for the current year
            $salaryRate = SalaryRate::whereYear('effective_date', $currentYear)
                ->orderBy('effective_date', 'desc')
                ->first();

            if (!$salaryRate) {
                $this->warn("No salary rate found for employee ID {$employee->id}");
                continue; // Skip if no salary rate found
            }

            // Find the salary rate detail that matches the years of service
            $salaryRateDetail = SalaryRateDetail::where('salary_rate_id', $salaryRate->id)
                ->where('years_of_service', '<=', $yearsOfService)
                ->orderBy('years_of_service', 'desc')
                ->first();

            if (!$salaryRateDetail) {
                $this->warn("No salary rate detail found for employee ID {$employee->id}");
                continue; // Skip if no rate per hour found
            }

            $salaryRatePerHour = $salaryRateDetail->rate_per_hour;

            // Update the employee's salary_rate_per_hour
            $employee->update(['salary_rate_per_hour' => $salaryRatePerHour]);

            $this->info("Updated salary_rate_per_hour for employee ID {$employee->id} to {$salaryRatePerHour}");
        }

        $this->info('Salary rates updated successfully.');
    }
}
