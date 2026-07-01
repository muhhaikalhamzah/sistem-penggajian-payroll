<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px">ID Cuti</th>
                <td>{{ $leave->id }}</td>
            </tr>
            <tr>
                <th>Nama Karyawan</th>
                <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }} ({{ $leave->employee->employee_number }})</td>
            </tr>
            <tr>
                <th>Tipe Cuti</th>
                <td>{{ $leave->leave_type }}</td>
            </tr>
            <tr>
                <th>Tanggal Mulai</th>
                <td>{{ $leave->start_date->format('d M Y') }}</td>
            </tr>
            <tr>
                <th>Tanggal Berakhir</th>
                <td>{{ $leave->end_date->format('d M Y') }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $leave->status }}</td>
            </tr>
            <tr>
                <th>Dibuat Pada</th>
                <td>{{ $leave->created_at->format('d M Y H:i') }}</td>
            </tr>
        </table>
    </div>
</x-app>