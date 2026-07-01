<?php

namespace App\Services;

use App\Models\Employee;

class Pph21Calculator
{
    /**
     * Calculate monthly PPh 21 based on employee's PTKP and monthly gross salary
     *
     * @param Employee $employee
     * @param float $monthlyGrossSalary
     * @return array [taxable_income, pph21_amount]
     */
    public function calculate(Employee $employee, float $monthlyGrossSalary): array
    {
        // 1. Calculate Annual Gross
        $annualGross = $monthlyGrossSalary * 12;

        // 2. Get PTKP value
        $ptkp = $this->getPtkpAmount($employee->ptkp_status);

        // 3. Calculate Taxable Income (PKP)
        $annualTaxable = $annualGross - $ptkp;
        
        // If income is below PTKP, no tax
        if ($annualTaxable <= 0) {
            return [
                'taxable_income' => 0,
                'pph21_amount' => 0
            ];
        }

        // Round down PKP to nearest thousand (aturan perpajakan umum)
        $annualTaxable = floor($annualTaxable / 1000) * 1000;

        // 4. Calculate Annual Tax using progressive rates
        $annualTax = $this->calculateProgressiveTax($annualTaxable);

        // 5. Monthly Tax
        $monthlyTax = $annualTax / 12;

        return [
            'taxable_income' => $annualTaxable,
            'pph21_amount' => $monthlyTax
        ];
    }

    /**
     * Get PTKP amount based on status string (TK/0, K/1, etc)
     */
    private function getPtkpAmount(string $status): float
    {
        // PTKP Base: 54,000,000 (TK/0)
        // Additional for married: 4,500,000
        // Additional per dependent (max 3): 4,500,000

        $base = 54000000;
        $addition = 4500000;

        return match ($status) {
            'TK/0' => $base,
            'TK/1' => $base + $addition,
            'TK/2' => $base + ($addition * 2),
            'TK/3' => $base + ($addition * 3),
            'K/0'  => $base + $addition,
            'K/1'  => $base + ($addition * 2),
            'K/2'  => $base + ($addition * 3),
            'K/3'  => $base + ($addition * 4),
            default => $base,
        };
    }

    /**
     * Calculate progressive tax based on UU HPP (Pasal 17)
     */
    private function calculateProgressiveTax(float $pkp): float
    {
        $tax = 0;

        // Lapisan 1: 5% (0 - 60jt)
        if ($pkp > 0) {
            $layer = min($pkp, 60000000);
            $tax += $layer * 0.05;
            $pkp -= $layer;
        }

        // Lapisan 2: 15% (60jt - 250jt)
        if ($pkp > 0) {
            $layer = min($pkp, 190000000); // 250 - 60
            $tax += $layer * 0.15;
            $pkp -= $layer;
        }

        // Lapisan 3: 25% (250jt - 500jt)
        if ($pkp > 0) {
            $layer = min($pkp, 250000000); // 500 - 250
            $tax += $layer * 0.25;
            $pkp -= $layer;
        }

        // Lapisan 4: 30% (500jt - 5M)
        if ($pkp > 0) {
            $layer = min($pkp, 4500000000); // 5000 - 500
            $tax += $layer * 0.30;
            $pkp -= $layer;
        }

        // Lapisan 5: 35% (> 5M)
        if ($pkp > 0) {
            $tax += $pkp * 0.35;
        }

        return $tax;
    }
}
