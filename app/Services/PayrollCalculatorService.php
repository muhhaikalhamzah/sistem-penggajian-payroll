<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\TaxConfig;
use App\Models\AttendanceRecord;
use Carbon\Carbon;

class PayrollCalculatorService
{
    public function calculate(Employee $employee, $month, $year)
    {
        $salaryStructure = $employee->salaryStructures()
                            ->where('effective_date', '<=', Carbon::createFromDate($year, $month, 1)->endOfMonth())
                            ->orderBy('effective_date', 'desc')
                            ->first();

        $basic_salary = $salaryStructure ? floatval($salaryStructure->basic_salary) : 0;
        
        // Sum allowances
        $allowances = 0;
        if (method_exists($employee, 'allowances')) {
            $allowances = floatval($employee->allowances()->sum('amount'));
        }

        // Calculate unpaid leaves (Cuti)
        // Simplified: Alpa (absent without reason)
        $alphaCount = AttendanceRecord::where('employee_id', $employee->id)
            ->whereMonth('record_date', $month)
            ->whereYear('record_date', $year)
            ->where('status', 'Alpa')
            ->count();
            
        // Simplified penalty: proportional basic salary per alpha day
        $workingDays = config('payroll.working_days_per_month', 22);
        $alpha_deduction = $workingDays > 0 ? ($basic_salary / $workingDays) * $alphaCount : 0;

        // Overtime Pay (PP 35/2021 Simplified to flat 1/173 for now, or just proportional)
        $overtimeHours = AttendanceRecord::where('employee_id', $employee->id)
            ->whereMonth('record_date', $month)
            ->whereYear('record_date', $year)
            ->sum('overtime_hours');
        
        // Asumsi lembur hari biasa flat 1.5x upah per jam untuk mempermudah (Atau mengikuti rumus 1.5 & 2)
        // Untuk penyederhanaan:
        $upah_sejam = $basic_salary / 173;
        $overtime_pay = 0;
        if ($overtimeHours > 0) {
            $jam_pertama = 1;
            $jam_berikutnya = $overtimeHours - 1;
            $overtime_pay = ($jam_pertama * 1.5 * $upah_sejam) + ($jam_berikutnya * 2 * $upah_sejam);
        }

        $allowances += $overtime_pay; // Tambahkan overtime ke allowance total

        // Other deductions
        $other_deductions = 0;
        if (method_exists($employee, 'deductions')) {
            $other_deductions = floatval($employee->deductions()->sum('amount'));
        }

        $total_deductions = $alpha_deduction + $other_deductions;

        // Calculate BPJS Karyawan
        $bpjs_kes_rate = TaxConfig::where('name', 'BPJS Kesehatan')->where('is_active', true)->value('rate_percentage') ?? 1;
        $bpjs_tk_rate = TaxConfig::where('name', 'BPJS Ketenagakerjaan')->where('is_active', true)->value('rate_percentage') ?? 3; // JHT 2% + JP 1%
        
        $bpjs_fee = ($basic_salary * ($bpjs_kes_rate + $bpjs_tk_rate)) / 100;

        // Asumsi BPJS Ditanggung Perusahaan (sebagai penambah Bruto)
        $bpjs_perusahaan_rate = 4.54; // Kes 4%, JKK 0.24%, JKM 0.3%
        $bpjs_perusahaan = ($basic_salary * $bpjs_perusahaan_rate) / 100;

        $penghasilan_bruto = $basic_salary + $allowances + $bpjs_perusahaan;
        
        $ptkp_status = strtoupper(trim($employee->ptkp_status ?? 'TK/0'));
        $kategori_ter = $this->getKategoriTer($ptkp_status);
        $ter_rate = $this->getTerRate($kategori_ter, $penghasilan_bruto);
        
        $pph21_tax = ($penghasilan_bruto * $ter_rate) / 100;

        // Insert or update TaxRecord
        \App\Models\TaxRecord::updateOrCreate(
            ['employee_id' => $employee->id, 'period' => str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . $year],
            ['taxable_income' => $penghasilan_bruto, 'pph21_amount' => $pph21_tax]
        );

        // THP = (Basic + Allowances) - (Deductions + BPJS Karyawan + PPh21)
        $net_income_monthly = ($basic_salary + $allowances) - ($total_deductions + $bpjs_fee);
        $net_salary = $net_income_monthly - $pph21_tax;

        return [
            'basic_salary' => $basic_salary,
            'allowance_total' => $allowances, // Termasuk lembur
            'deduction_total' => $total_deductions,
            'bpjs_fee' => $bpjs_fee,
            'pph21_tax' => $pph21_tax,
            'net_salary' => $net_salary > 0 ? $net_salary : 0
        ];
    }

    private function getKategoriTer($ptkp)
    {
        $katA = ['TK/0', 'TK/1', 'K/0'];
        $katB = ['TK/2', 'TK/3', 'K/1', 'K/2'];
        
        if (in_array($ptkp, $katA)) return 'A';
        if (in_array($ptkp, $katB)) return 'B';
        return 'C'; // K/3
    }

    private function getTerRate($kategori, $bruto)
    {
        if ($kategori === 'A') {
            if ($bruto <= 5400000) return 0;
            if ($bruto <= 5650000) return 0.5;
            if ($bruto <= 5950000) return 0.75;
            if ($bruto <= 6300000) return 1.0;
            if ($bruto <= 6750000) return 1.25;
            if ($bruto <= 7150000) return 1.5;
            if ($bruto <= 7300000) return 1.75;
            if ($bruto <= 9200000) return 2.0;
            if ($bruto <= 9650000) return 2.25;
            if ($bruto <= 10050000) return 2.5;
            if ($bruto <= 10350000) return 3.0;
            if ($bruto <= 10700000) return 4.0;
            if ($bruto <= 11050000) return 5.0;
            return 6.0; // Disederhanakan untuk batas atas
        } elseif ($kategori === 'B') {
            if ($bruto <= 6200000) return 0;
            if ($bruto <= 6500000) return 0.5;
            if ($bruto <= 6850000) return 0.75;
            if ($bruto <= 7300000) return 1.0;
            if ($bruto <= 9200000) return 1.5;
            if ($bruto <= 9800000) return 2.0;
            if ($bruto <= 10200000) return 2.5;
            if ($bruto <= 10500000) return 3.0;
            return 4.0; // Disederhanakan
        } else {
            // Kategori C
            if ($bruto <= 6600000) return 0;
            if ($bruto <= 6950000) return 0.5;
            if ($bruto <= 7350000) return 0.75;
            if ($bruto <= 7800000) return 1.0;
            if ($bruto <= 8850000) return 1.25;
            if ($bruto <= 9800000) return 1.5;
            if ($bruto <= 10950000) return 2.0;
            return 3.0; // Disederhanakan
        }
    }
}
