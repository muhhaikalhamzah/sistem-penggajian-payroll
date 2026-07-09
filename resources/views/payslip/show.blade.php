<x-app>
    <x-slot:title>Detail Slip Gaji</x-slot:title>
<div class="pagetitle">
    <h1>Detail Slip Gaji</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('payslips.index') }}">Slip Gaji</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Slip Gaji: {{ $payslip->period }}</h5>
                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label text-muted">Nama Karyawan</div>
                        <div class="col-lg-9 col-md-8">{{ $payslip->employee->first_name }} {{ $payslip->employee->last_name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label text-muted">Periode</div>
                        <div class="col-lg-9 col-md-8">{{ $payslip->period }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label text-muted">Tanggal Pembayaran</div>
                        <div class="col-lg-9 col-md-8">{{ \Carbon\Carbon::parse($payslip->payment_date)->isoFormat('D MMMM Y') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label text-muted">Status</div>
                        <div class="col-lg-9 col-md-8">
                            @if($payslip->status == 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @elseif($payslip->status == 'approved')
                                <span class="badge bg-primary">Approved</span>
                            @elseif($payslip->status == 'paid')
                                <span class="badge bg-success">Paid</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <h6 class="fw-bold">Penerimaan (Earnings)</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Gaji Pokok</span>
                                <span>Rp {{ number_format($payslip->basic_salary, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tunjangan Total</span>
                                <span>Rp {{ number_format($payslip->allowance_total, 0, ',', '.') }}</span>
                            </div>
                            <hr class="my-1">
                            <div class="d-flex justify-content-between mb-2 fw-bold">
                                <span>Total Penerimaan Kotor</span>
                                <span>Rp {{ number_format($payslip->basic_salary + $payslip->allowance_total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <h6 class="fw-bold">Potongan (Deductions)</h6>
                            <div class="d-flex justify-content-between mb-1 text-muted small">
                                <span>Potongan Kehadiran & Lainnya</span>
                                <span>Rp {{ number_format($payslip->deduction_total, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-muted small">
                                <span>BPJS Kesehatan & TK</span>
                                <span>Rp {{ number_format($payslip->bpjs_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-muted small">
                                <span>Pajak PPh 21</span>
                                <span>Rp {{ number_format($payslip->pph21_tax, 0, ',', '.') }}</span>
                            </div>
                            <hr class="my-1">
                            <div class="d-flex justify-content-between mb-2 fw-bold">
                                <span>Total Potongan</span>
                                <span>Rp {{ number_format($payslip->deduction_total + $payslip->bpjs_fee + $payslip->pph21_tax, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-lg-12 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold text-success mb-0">Take Home Pay (Gaji Bersih)</h5>
                            <h4 class="fw-bold text-success mb-0">Rp {{ number_format($payslip->net_salary, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-center">
                        @if($payslip->status === 'draft' && in_array(strtolower(auth()->user()->role), ['superadmin', 'admin']))
                            <form action="{{ route('payslips.approve', $payslip->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin menyetujui slip gaji ini?')"><i class="bi bi-check-circle"></i> Approve</button>
                            </form>
                        @endif
                        <a href="{{ route('payslips.print', $payslip->id) }}" target="_blank" class="btn btn-secondary"><i class="bi bi-printer"></i> Cetak / Simpan PDF</a>
                        <a href="{{ route('payslips.index') }}" class="btn btn-outline-secondary">Kembali ke Daftar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</x-app>
