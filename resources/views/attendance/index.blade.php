<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('attendance.create') }}" role="button">Input Absensi Manual</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Karyawan (NIK)</th>
                        <th scope="col">Masuk</th>
                        <th scope="col">Keluar</th>
                        <th scope="col">Jam Lembur</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $att)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $att->record_date->format('d M Y') }}</td>
                            <td>{{ $att->employee->first_name }} {{ $att->employee->last_name }} <br><small class="text-muted">{{ $att->employee->employee_number }}</small></td>
                            <td>{{ $att->check_in ? date('H:i', strtotime($att->check_in)) : '-' }}</td>
                            <td>{{ $att->check_out ? date('H:i', strtotime($att->check_out)) : '-' }}</td>
                            <td>
                                @if($att->overtime_hours > 0)
                                    <span class="badge bg-warning">{{ $att->overtime_hours }} Jam</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($att->status === 'Present')
                                    <span class="badge bg-success">Hadir</span>
                                @elseif($att->status === 'Absent')
                                    <span class="badge bg-danger">Alpa</span>
                                @else
                                    <span class="badge bg-secondary">Cuti/Izin</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('attendance.show', $att) }}" class="btn btn-info btn-sm">
                                    <i class='bx bx-info-circle'></i>
                                </a>
                                <a href="{{ route('attendance.edit', $att) }}" class="btn btn-warning btn-sm">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-route="{{ route('attendance.destroy', $att) }}">
                                    <i class='bx bx-trash'></i>
                                </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @push('scripts')
        <script>
            $('#data-table').on('click', '.btn-delete', function() {
                $('#form-delete').attr('action', $(this).data('route'))
            })
        </script>
    @endpush
</x-app>