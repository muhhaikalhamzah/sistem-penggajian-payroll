<x-app>
    <x-slot:title>Detail Slip Gaji</x-slot:title>
<div class="pagetitle">
    <h1>Payslip Details</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('payslips.index') }}">Payslips</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Payslip: {{ $payslip->period }}</h5>
                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label text-muted">Employee Name</div>
                        <div class="col-lg-9 col-md-8">{{ $payslip->employee->user->name ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label text-muted">Period</div>
                        <div class="col-lg-9 col-md-8">{{ $payslip->period }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label text-muted">Payment Date</div>
                        <div class="col-lg-9 col-md-8">{{ \Carbon\Carbon::parse($payslip->payment_date)->format('d F Y') }}</div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <h6 class="fw-bold">Earnings</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Gross Salary</span>
                                <span>Rp {{ number_format($payslip->gross_salary, 0, ',', '.') }}</span>
                            </div>
                            <div class="text-muted small">
                                <em>Includes basic salary and all allowances.</em>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <h6 class="fw-bold">Deductions</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Deductions</span>
                                <span>Rp {{ number_format($payslip->total_deductions, 0, ',', '.') }}</span>
                            </div>
                            <div class="text-muted small">
                                <em>Includes absences, other deductions, and PPh21.</em>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-lg-12 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold text-success mb-0">Take Home Pay</h5>
                            <h4 class="fw-bold text-success mb-0">Rp {{ number_format($payslip->net_salary, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <a href="{{ route('payslips.print', $payslip->id) }}" target="_blank" class="btn btn-secondary"><i class="bi bi-printer"></i> Print / Save as PDF</a>
                        <a href="{{ route('payslips.index') }}" class="btn btn-outline-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</x-app>
