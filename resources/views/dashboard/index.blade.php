<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <!-- Welcome Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-3">
                        <i class='bx bx-smile text-primary me-2'></i>
                        Selamat Datang, {{ Auth::user()->name }}!
                    </h3>
                    <p class="text-muted mb-0">
                        Anda login sebagai <span class="badge bg-primary">{{ Auth::user()->role }}</span>
                    </p>
                    <p class="text-muted mt-2">
                        <i class='bx bx-time-five me-1'></i>
                        {{ now()->isoFormat('dddd, D MMMM YYYY - HH:mm') }}
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('niceadmin/img/noprofil.png') }}"
                        alt="Avatar" class="img-fluid rounded-circle border border-3 border-primary"
                        style="max-width: 150px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    @if(in_array(Auth::user()->role, ['Superadmin', 'Admin', 'hr']))
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small">Total User</p>
                            <h2 class="fw-bold mb-0">{{ $totalUsers }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class='bx bx-user fs-2 text-primary'></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-primary bg-opacity-10 border-0 py-2">
                    <small class="text-primary fw-semibold">
                        <i class='bx bx-trending-up me-1'></i>
                        Semua user terdaftar
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small">Superadmin</p>
                            <h2 class="fw-bold mb-0">{{ $superadminCount }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class='bx bx-shield fs-2 text-success'></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-success bg-opacity-10 border-0 py-2">
                    <small class="text-success fw-semibold">
                        <i class='bx bx-check-circle me-1'></i>
                        Akses penuh
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small">Admin</p>
                            <h2 class="fw-bold mb-0">{{ $adminCount }}</h2>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class='bx bx-user-check fs-2 text-info'></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-info bg-opacity-10 border-0 py-2">
                    <small class="text-info fw-semibold">
                        <i class='bx bx-user-circle me-1'></i>
                        Akses standar
                    </small>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(!empty($payslipChartData))
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold">
                <i class='bx bx-line-chart me-2 text-primary'></i>
                Riwayat Gaji (Trend)
            </h5>
        </div>
        <div class="card-body">
            <canvas id="payslipChart" style="max-height: 300px;"></canvas>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold">
                <i class='bx bx-rocket me-2 text-primary'></i>
                Aksi Cepat
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3 mt-2">
                @if(in_array(Auth::user()->role, ['Superadmin', 'Admin', 'hr']))
                <div class="col-md-3">
                    <a href="{{ route('user.index') }}" class="text-decoration-none">
                        <div class="card border border-primary border-opacity-25 h-100 hover-shadow">
                            <div class="card-body text-center mt-4">
                                <i class='bx bx-user-plus fs-1 text-primary mb-2'></i>
                                <h6 class="mb-0">Kelola User</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
                @if(in_array(Auth::user()->role, ['Superadmin', 'Admin']))
                <div class="col-md-3">
                    <a href="{{ route('setting.index') }}" class="text-decoration-none">
                        <div class="card border border-success border-opacity-25 h-100 hover-shadow">
                            <div class="card-body text-center mt-4"">
                                <i class='bx bx-cog fs-1 text-success mb-2'></i>
                                <h6 class=" mb-0">Pengaturan</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
                <div class="col-md-3">
                    <a href="{{ route('dashboard.show') }}" class="text-decoration-none">
                        <div class="card border border-info border-opacity-25 h-100 hover-shadow">
                            <div class="card-body text-center mt-4"">
                                <i class='bx bx-user-circle fs-1 text-info mb-2'></i>
                                <h6 class=" mb-0">Profil Saya</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @if(in_array(Auth::user()->role, ['Superadmin', 'Admin']))
                <div class="col-md-3">
                    <a href="{{ route('dashboard.edit') }}" class="text-decoration-none">
                        <div class="card border border-warning border-opacity-25 h-100 hover-shadow">
                            <div class="card-body text-center mt-4">
                                <i class='bx bx-edit fs-1 text-warning mb-2'></i>
                                <h6 class=" mb-0">Edit Profil</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class='bx bx-info-circle me-2 text-primary'></i>
                        Informasi Sistem
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 pt-4">
                        <li class="mb-2">
                            <i class='bx bx-check-circle text-success me-2'></i>
                            <strong>Versi Laravel:</strong> {{ app()->version() }}
                        </li>
                        <li class="mb-2">
                            <i class='bx bx-check-circle text-success me-2'></i>
                            <strong>Versi PHP:</strong> {{ PHP_VERSION }}
                        </li>
                        <li class="mb-2">
                            <i class='bx bx-check-circle text-success me-2'></i>
                            <strong>Lingkungan:</strong> {{ config('app.env') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0 pt-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class='bx bx-user me-2 text-primary'></i>
                        Akun Anda
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class='bx bx-envelope text-primary me-2'></i>
                            <strong>Email:</strong> {{ Auth::user()->email }}
                        </li>
                        <li class="mb-2">
                            <i class='bx bx-calendar text-primary me-2'></i>
                            <strong>Terdaftar Sejak:</strong> {{ Auth::user()->created_at->format('d M Y') }}
                        </li>
                        <li class="mb-2">
                            <i class='bx bx-time text-primary me-2'></i>
                            <strong>Terakhir Diperbarui:</strong> {{ Auth::user()->updated_at->diffForHumans() }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    @push('modals')
    @endpush

    @push('scripts')
    @if(!empty($payslipChartData))
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const chartData = {!! $payslipChartData !!};
            new Chart(document.querySelector('#payslipChart'), {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Gaji Bersih (Take Home Pay)',
                        data: chartData.data,
                        fill: true,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    @endif
    @endpush

</x-app>
