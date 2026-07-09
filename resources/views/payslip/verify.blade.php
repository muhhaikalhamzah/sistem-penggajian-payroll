<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Dokumen Valid</h3>
                    <p class="text-muted mb-4">Slip Gaji ini diterbitkan resmi oleh sistem HRIS perusahaan.</p>
                    
                    <ul class="list-group list-group-flush text-start mb-4">
                        <li class="list-group-item px-0">
                            <strong>Nama Karyawan:</strong> <br>
                            {{ $payslip->employee->first_name }} {{ $payslip->employee->last_name }}
                        </li>
                        <li class="list-group-item px-0">
                            <strong>Periode:</strong> <br>
                            {{ $payslip->period }}
                        </li>
                        <li class="list-group-item px-0">
                            <strong>Tanggal Terbit:</strong> <br>
                            {{ \Carbon\Carbon::parse($payslip->payment_date)->isoFormat('D MMMM Y') }}
                        </li>
                    </ul>
                    
                    <div class="alert alert-success m-0" role="alert">
                        <strong>Status:</strong> {{ strtoupper($payslip->status) }}
                    </div>
                </div>
                <div class="card-footer bg-white text-center border-0 pb-4">
                    <small class="text-muted">&copy; {{ date('Y') }} HRIS System</small>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
