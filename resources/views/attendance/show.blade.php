<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('attendance.edit', $attendance) }}" class="btn btn-warning">Edit</a>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px">ID</th>
                <td>{{ $attendance->id }}</td>
            </tr>
            <tr>
                <th>Karyawan (NIK)</th>
                <td>{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }} ({{ $attendance->employee->employee_number }})</td>
            </tr>
            <tr>
                <th>Tanggal Rekap</th>
                <td>{{ $attendance->record_date->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>Jam Masuk</th>
                <td>{{ $attendance->check_in ? date('H:i', strtotime($attendance->check_in)) : '-' }}</td>
            </tr>
            <tr>
                <th>Jam Keluar</th>
                <td>{{ $attendance->check_out ? date('H:i', strtotime($attendance->check_out)) : '-' }}</td>
            </tr>
            <tr>
                <th>Jam Lembur (Overtime)</th>
                <td>
                    @if($attendance->overtime_hours > 0)
                        <span class="badge bg-warning text-dark">{{ $attendance->overtime_hours }} Jam</span>
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <th>Status Kehadiran</th>
                <td>
                    @if($attendance->status === 'Hadir')
                        <span class="badge bg-success">Hadir</span>
                    @elseif($attendance->status === 'Alpa')
                        <span class="badge bg-danger">Alpa</span>
                    @else
                        <span class="badge bg-secondary">Cuti/Izin</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Dibuat Pada</th>
                <td>{{ $attendance->created_at->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <th>Terakhir Diubah</th>
                <td>{{ $attendance->updated_at->format('d M Y H:i') }}</td>
            </tr>
        </table>
    </div>
</x-app>
