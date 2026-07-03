<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $payslip->period }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; padding: 20px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .details { margin-bottom: 30px; }
        .details table { width: 100%; }
        .details td { padding: 5px 0; }
        .table-payslip { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table-payslip th, .table-payslip td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .table-payslip th { background-color: #f4f4f4; }
        .thp { text-align: right; font-size: 1.2em; font-weight: bold; }
        .footer { margin-top: 50px; text-align: center; font-size: 0.9em; color: #777; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Cetak PDF</button>
        <a href="{{ route('payslips.index') }}" style="margin-left: 10px; text-decoration: none; color: #007bff;">Kembali</a>
    </div>

    <div class="header">
        <h2>SLIP GAJI</h2>
        @php $setting = \App\Models\Setting::first(); @endphp
        <p>{{ $setting->app_name ?? 'Perusahaan' }}</p>
    </div>

    <div class="details">
        <table>
            <tr>
                <td width="20%"><strong>Nama Karyawan:</strong></td>
                <td width="30%">{{ $payslip->employee->first_name }} {{ $payslip->employee->last_name }}</td>
                <td width="20%"><strong>Periode:</strong></td>
                <td width="30%">{{ $payslip->period }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Pembayaran:</strong></td>
                <td>{{ \Carbon\Carbon::parse($payslip->payment_date)->isoFormat('D MMMM Y') }}</td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>

    <table class="table-payslip">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th style="text-align: right;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gaji Kotor (Pokok + Tunjangan)</td>
                <td style="text-align: right;">Rp {{ number_format($payslip->gross_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding-left: 20px; font-size: 0.9em; color: #555;">- Potongan Dasar (Master)</td>
                <td style="text-align: right; font-size: 0.9em; color: #555;">Rp {{ number_format($payslip->employee->deductions->sum('amount'), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding-left: 20px; font-size: 0.9em; color: #555;">- Potongan Alpha ({{ $alphaCount }} Hari)</td>
                <td style="text-align: right; font-size: 0.9em; color: #555;">Rp {{ number_format($alphaDeduction, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding-left: 20px; font-size: 0.9em; color: #555;">- Pajak PPh 21</td>
                <td style="text-align: right; font-size: 0.9em; color: #555;">Rp {{ number_format($taxRecord->pph21_amount ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Potongan</strong></td>
                <td style="text-align: right; color: red;">- Rp {{ number_format($payslip->total_deductions, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="text-align: right; font-weight: bold;">Take Home Pay (Gaji Bersih):</td>
                <td class="thp">Rp {{ number_format($payslip->net_salary, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis oleh komputer. Tidak diperlukan tanda tangan.</p>
    </div>
</body>
</html>
