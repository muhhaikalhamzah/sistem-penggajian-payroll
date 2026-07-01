<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a href="{{ route('employee.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('employee.edit', $employee) }}" class="btn btn-warning">Edit</a>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px">ID</th>
                <td>{{ $employee->id }}</td>
            </tr>
            <tr>
                <th>Nomor Karyawan (NIK)</th>
                <td>{{ $employee->employee_number }}</td>
            </tr>
            <tr>
                <th>Nama Lengkap</th>
                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
            </tr>
            <tr>
                <th>Departemen</th>
                <td>{{ $employee->department->name }}</td>
            </tr>
            <tr>
                <th>Jabatan</th>
                <td>{{ $employee->position->title }}</td>
            </tr>
            <tr>
                <th>Status PTKP</th>
                <td>{{ $employee->ptkp_status }}</td>
            </tr>
            <tr>
                <th>Tanggal Bergabung</th>
                <td>{{ $employee->join_date->format('d M Y') }}</td>
            </tr>
            <tr>
                <th>Tertaut Akun (User)</th>
                <td>{{ $employee->user ? $employee->user->email : 'Belum tertaut' }}</td>
            </tr>
            <tr>
                <th>Dibuat Pada</th>
                <td>{{ $employee->created_at->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <th>Terakhir Diubah</th>
                <td>{{ $employee->updated_at->format('d M Y H:i') }}</td>
            </tr>
        </table>
    </div>
</x-app>