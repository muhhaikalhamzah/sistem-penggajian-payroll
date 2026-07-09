<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card shadow-lg p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Karyawan (NIK)</th>
                        <th scope="col">Tipe Cuti</th>
                        <th scope="col">Mulai</th>
                        <th scope="col">Selesai</th>
                        <th scope="col">Status</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaves as $leave)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }} ({{ $leave->employee->employee_number }})</td>
                            <td>{{ $leave->leave_type }}</td>
                            <td>{{ $leave->start_date->format('d M Y') }}</td>
                            <td>{{ $leave->end_date->format('d M Y') }}</td>
                            <td>
                                @if($leave->status == 'Menunggu')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($leave->status == 'Disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('leave-requests.show', $leave) }}" class="btn btn-info btn-sm">
                                        <i class='bx bx-info-circle'></i>
                                    </a>
                                    @if($leave->status == 'Menunggu')
                                        <form action="{{ route('leave-requests.update', $leave) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Disetujui">
                                            <button type="submit" class="btn btn-success btn-sm" title="Setujui">
                                                <i class='bx bx-check'></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('leave-requests.update', $leave) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Ditolak">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Tolak">
                                                <i class='bx bx-x'></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>