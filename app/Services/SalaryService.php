<?php

namespace App\Services;

use App\Models\Salary;
use App\Models\DailySalary;
use App\Models\Presence;
use App\Models\SalaryRate;
use App\Models\User;
use Carbon\Carbon;

class SalaryService
{
    /**
     * Hitung dan simpan gaji harian saat presensi
     */
    public function calculateDailySalary(Presence $presence)
    {
        // Pastikan presence sudah check-out
        if (!$presence->check_out) {
            return null;
        }

        $user = $presence->createdBy;
        $employee = $user->employees()->first();

        if (!$employee || !$employee->join_date) {
            throw new \Exception('Data karyawan tidak ditemukan');
        }

        // Hitung masa kerja
        $yearsOfService = Carbon::parse($employee->join_date)
            ->diffInYears(Carbon::parse($presence->check_in));

        // Ambil rate gaji yang berlaku
        $salaryRate = SalaryRate::where('effective_date', '<=', $presence->check_in)
            ->latest('effective_date')
            ->first();

        if (!$salaryRate) {
            throw new \Exception('Rate gaji tidak ditemukan');
        }

        // Ambil rate per jam sesuai masa kerja
        $rateDetail = $salaryRate->salaryRateDetails()
            ->where('years_of_service', '<=', $yearsOfService)
            ->orderBy('years_of_service', 'desc')
            ->first();

        if (!$rateDetail) {
            throw new \Exception('Detail rate tidak ditemukan');
        }

        // Hitung jam kerja
        $checkIn = Carbon::parse($presence->check_in);
        $checkOut = Carbon::parse($presence->check_out);
        $workHours = $checkOut->diffInHours($checkIn);

        // Hitung gaji hari ini
        $dailySalary = $workHours * $rateDetail->rate_per_hour;

        // Simpan atau update gaji harian
        return DailySalary::updateOrCreate(
            [
                'presence_id' => $presence->id,
                'user_id' => $user->id,
                'date' => $checkIn->toDateString()
            ],
            [
                'work_hours' => $workHours,
                'rate_per_hour' => $rateDetail->rate_per_hour,
                'amount' => $dailySalary,
                'salary_rate_id' => $salaryRate->id,
                'years_of_service' => $yearsOfService
            ]
        );
    }

    /**
     * Generate atau update gaji bulanan
     */
    public function generateMonthlySalary($userId, $year, $month)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $user = User::findOrFail($userId);
        $employee = $user->employees()->first();

        if (!$employee) {
            throw new \Exception('Data karyawan tidak ditemukan');
        }

        // Ambil semua gaji harian dalam periode
        $dailySalaries = DailySalary::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $totalWorkHours = $dailySalaries->sum('work_hours');
        $totalAmount = $dailySalaries->sum('amount');

        // Hitung tunjangan dan potongan
        $allowances = [
            'transport' => $this->calculateTransportAllowance($dailySalaries->count()),
            'meal' => $this->calculateMealAllowance($dailySalaries->count())
        ];

        $deductions = [
            'late_penalties' => $this->calculateLatePenalties($userId, $startDate, $endDate),
            'tax' => $this->calculateTax($totalAmount)
        ];

        // Total gaji setelah tunjangan dan potongan
        $finalSalary = $totalAmount +
            array_sum($allowances) -
            array_sum($deductions);

        // Simpan atau update gaji bulanan
        return Salary::updateOrCreate(
            [
                'user_id' => $userId,
                'period_start' => $startDate,
                'period_end' => $endDate
            ],
            [
                'years_of_service' => $dailySalaries->last()->years_of_service ?? 0,
                'total_work_days' => $dailySalaries->count(),
                'total_hours' => $totalWorkHours,
                'base_salary' => $totalAmount,
                'allowances' => $allowances,
                'deductions' => $deductions,
                'total_salary' => $finalSalary,
                'status' => Salary::STATUS_DRAFT
            ]
        );
    }

    /**
     * Helper methods untuk menghitung tunjangan dan potongan
     */
    private function calculateTransportAllowance($workDays)
    {
        return $workDays * 25000; // Rp 25.000 per hari
    }

    private function calculateMealAllowance($workDays)
    {
        return $workDays * 20000; // Rp 20.000 per hari
    }

    private function calculateLatePenalties($userId, $startDate, $endDate)
    {
        return Presence::where('created_by_id', $userId)
            ->whereBetween('check_in', [$startDate, $endDate])
            ->where('check_in_status', 'terlambat')
            ->get()
            ->sum(function ($presence) {
                // Misal: denda Rp 10.000 per keterlambatan
                return 10000;
            });
    }

    private function calculateTax($amount)
    {
        // Implementasi perhitungan pajak
        return 0; // Sementara return 0
    }
}
