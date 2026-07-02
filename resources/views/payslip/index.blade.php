@extends('layouts.app')

@section('title', 'Payslips')

@section('content')
<div class="pagetitle">
    <h1>Payslips</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
            <li class="breadcrumb-item active">Payslips</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Payslips List</h5>

                    @if(auth()->user()->hasRole('finance') || auth()->user()->hasRole('Superadmin'))
                    <div class="mb-3">
                        <a href="{{ route('payslips.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Generate Payslips</a>
                    </div>
                    @endif

                    <table class="table table-bordered table-striped datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                @if(auth()->user()->hasRole('finance') || auth()->user()->hasRole('Superadmin'))
                                <th>Employee Name</th>
                                @endif
                                <th>Period</th>
                                <th>Net Salary</th>
                                <th>Payment Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payslips as $key => $payslip)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                @if(auth()->user()->hasRole('finance') || auth()->user()->hasRole('Superadmin'))
                                <td>{{ $payslip->employee->user->name ?? 'N/A' }}</td>
                                @endif
                                <td>{{ $payslip->period }}</td>
                                <td>Rp {{ number_format($payslip->net_salary, 0, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($payslip->payment_date)->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('payslips.show', $payslip->id) }}" class="btn btn-info btn-sm"><i class="bi bi-eye"></i> View</a>
                                    <a href="{{ route('payslips.print', $payslip->id) }}" target="_blank" class="btn btn-secondary btn-sm"><i class="bi bi-printer"></i> Print</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ (auth()->user()->hasRole('finance') || auth()->user()->hasRole('Superadmin')) ? 6 : 5 }}" class="text-center">No payslips found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
