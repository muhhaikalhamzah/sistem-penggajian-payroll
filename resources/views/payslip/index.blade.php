<x-app>
    <x-slot:title>Daftar Slip Gaji</x-slot:title>
<div class="pagetitle">
    <h1>Slip Gaji</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
            <li class="breadcrumb-item active">Slip Gaji</li>
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
                    <h5 class="card-title">Daftar Slip Gaji</h5>

                    @if(auth()->user()->hasRole('finance') || auth()->user()->hasRole('Superadmin') || in_array(strtolower(auth()->user()->role), ['admin', 'superadmin']))
                    <div class="mb-3 d-flex gap-2 align-items-center">
                        @if(auth()->user()->hasRole('finance') || auth()->user()->hasRole('Superadmin'))
                            <a href="{{ route('payslips.generate') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Buat Slip Gaji</a>
                            <a href="{{ route('payslips.export.excel') }}" class="btn btn-success"><i class="bi bi-file-earmark-excel"></i> Export Excel</a>
                        @endif

                        @if(in_array(strtolower(auth()->user()->role), ['superadmin', 'admin']) && $payslips->where('status', 'draft')->count() > 0)
                            <form action="{{ route('payslips.approve_all') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-info text-white" onclick="return confirm('Apakah Anda yakin menyetujui SEMUA slip gaji berstatus Draft?')">
                                    <i class="bi bi-check-all"></i> Approve Semua Draft
                                </button>
                            </form>
                        @endif
                    </div>
                    @endif

                    <table class="table table-bordered table-striped datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                @if(auth()->user()->hasRole('finance') || in_array(auth()->user()->role, ['Superadmin', 'Admin']))
                                <th>Nama Pegawai</th>
                                @endif
                                <th>Periode</th>
                                <th>Gaji Bersih</th>
                                <th>Status</th>
                                <th>Tanggal Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payslips as $key => $payslip)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                @if(auth()->user()->hasRole('finance') || in_array(auth()->user()->role, ['Superadmin', 'Admin']))
                                <td>{{ $payslip->employee->user->name ?? 'N/A' }}</td>
                                @endif
                                <td>{{ $payslip->period }}</td>
                                <td>Rp {{ number_format($payslip->net_salary, 0, ',', '.') }}</td>
                                <td>
                                    @if($payslip->status == 'draft')
                                        <span class="badge bg-secondary">Draft</span>
                                    @elseif($payslip->status == 'approved')
                                        <span class="badge bg-primary">Approved</span>
                                    @elseif($payslip->status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($payslip->payment_date)->format('d M Y') }}</td>
                                <td>
                                    @if($payslip->status === 'draft' && in_array(strtolower(auth()->user()->role), ['superadmin', 'admin']))
                                        <form action="{{ route('payslips.approve', $payslip->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Apakah Anda yakin menyetujui slip gaji ini?')"><i class="bi bi-check-circle"></i> Approve</button>
                                        </form>
                                    @endif
                                    @can('view', $payslip)
                                        <a href="{{ route('payslips.show', $payslip->id) }}" class="btn btn-info btn-sm"><i class="bi bi-eye"></i> Detail</a>
                                        <a href="{{ route('payslips.print', $payslip->id) }}" target="_blank" class="btn btn-secondary btn-sm"><i class="bi bi-printer"></i> Cetak</a>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ (auth()->user()->hasRole('finance') || in_array(auth()->user()->role, ['Superadmin', 'Admin'])) ? 7 : 6 }}" class="text-center">Tidak ada data slip gaji.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</section>
</x-app>
