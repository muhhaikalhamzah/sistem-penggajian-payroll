<?php

namespace App\Exports;

use App\Models\Payslip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayslipsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Payslip::with('employee')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Karyawan',
            'Periode',
            'Gaji Pokok',
            'Tunjangan',
            'Potongan',
            'PPh 21',
            'BPJS',
            'Gaji Bersih',
            'Status',
            'Tanggal Pembayaran'
        ];
    }

    public function map($payslip): array
    {
        return [
            $payslip->id,
            $payslip->employee ? $payslip->employee->first_name . ' ' . $payslip->employee->last_name : '-',
            $payslip->period,
            $payslip->basic_salary,
            $payslip->allowances,
            $payslip->deductions,
            $payslip->tax_amount,
            $payslip->bpjs_amount,
            $payslip->net_salary,
            $payslip->status,
            $payslip->payment_date ? \Carbon\Carbon::parse($payslip->payment_date)->format('Y-m-d') : '-'
        ];
    }
}
