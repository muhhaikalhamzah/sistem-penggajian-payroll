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
        .deductions { float: right; width: 48%; }
        .total-section { clear: both; margin-top: 20px; border-top: 2px solid #000; padding-top: 10px; }
        .total-row { font-weight: bold; font-size: 16px; }
        .text-right { text-align: right; }
        .footer { margin-top: 50px; text-align: center; font-size: 0.9em; color: #777; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    @if(!request()->has('download'))
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #007bff; color: #fff; border: none; border-radius: 4px;">Cetak PDF</button>
        <a href="{{ route('payslips.index') }}" style="margin-left: 10px; text-decoration: none; color: #007bff;">Kembali</a>
    </div>
    @endif

    <div class="container">
        <div class="header">
            <h2>SLIP GAJI KARYAWAN</h2>
            <p><strong>Periode:</strong> {{ $payslip->period }}</p>
        </div>

        <div class="info-section">
            <table>
                <tr>
                    <td width="150"><strong>Nama Karyawan</strong></td>
                    <td width="10">:</td>
                    <td>{{ $payslip->employee->first_name }} {{ $payslip->employee->last_name }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Pembayaran</strong></td>
                    <td>:</td>
                    <td>{{ \Carbon\Carbon::parse($payslip->payment_date)->isoFormat('D MMMM Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Status</strong></td>
                    <td>:</td>
                    <td>{{ strtoupper($payslip->status) }}</td>
                </tr>
            </table>
        </div>

        <div class="earnings">
            <div class="section-title">PENERIMAAN</div>
            <table class="details-table" style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td>Gaji Pokok</td>
                    <td class="text-right">Rp {{ number_format($payslip->basic_salary, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Tunjangan Total</td>
                    <td class="text-right">Rp {{ number_format($payslip->allowance_total, 0, ',', '.') }}</td>
                </tr>
                <tr style="font-weight: bold;">
                    <td>Total Penerimaan</td>
                    <td class="text-right">Rp {{ number_format($payslip->basic_salary + $payslip->allowance_total, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="deductions">
            <div class="section-title">POTONGAN</div>
            <table class="details-table" style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td>Potongan Kehadiran & Lainnya</td>
                    <td class="text-right">Rp {{ number_format($payslip->deduction_total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>BPJS Kesehatan & TK</td>
                    <td class="text-right">Rp {{ number_format($payslip->bpjs_fee, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Pajak PPh 21</td>
                    <td class="text-right">Rp {{ number_format($payslip->pph21_tax, 0, ',', '.') }}</td>
                </tr>
                <tr style="font-weight: bold;">
                    <td>Total Potongan</td>
                    <td class="text-right">Rp {{ number_format($payslip->deduction_total + $payslip->bpjs_fee + $payslip->pph21_tax, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="total-section">
            <table width="100%">
                <tr class="total-row">
                    <td>TAKE HOME PAY (Gaji Bersih)</td>
                    <td class="text-right">Rp {{ number_format($payslip->net_salary, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        
        <div style="margin-top: 40px; text-align: center;">
            <p style="margin-bottom: 5px;">Scan untuk memvalidasi slip gaji:</p>
            <div>
                <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(100)->generate(URL::signedRoute('payslips.verify', ['payslip' => $payslip->id]))) }}" alt="QR Code" width="100" height="100">
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis oleh komputer. Tidak diperlukan tanda tangan.</p>
    </div>
</body>
</html>
