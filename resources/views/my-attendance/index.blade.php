<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3 mb-4">
        <h5 class="card-title">Aksi Hari Ini ({{ now()->format('d M Y') }})</h5>
        <div class="d-flex gap-2">
            @if(!$todayAttendance)
                <form action="{{ route('my-attendance.check_in') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary"><i class="bi bi-box-arrow-in-right"></i> Check In Sekarang</button>
                </form>
            @elseif(!$todayAttendance->check_out)
                <button class="btn btn-success" disabled><i class="bi bi-check-circle"></i> Sudah Check In ({{ date('H:i', strtotime($todayAttendance->check_in)) }})</button>
                <form action="{{ route('my-attendance.check_out') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning"><i class="bi bi-box-arrow-right"></i> Check Out Sekarang</button>
                </form>
            @else
                <button class="btn btn-success" disabled><i class="bi bi-check-circle"></i> Sudah Check In ({{ date('H:i', strtotime($todayAttendance->check_in)) }})</button>
                <button class="btn btn-secondary" disabled><i class="bi bi-check-circle"></i> Sudah Check Out ({{ date('H:i', strtotime($todayAttendance->check_out)) }})</button>
            @endif
        </div>
    </div>

    <div class="card shadow-lg p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Masuk</th>
                        <th scope="col">Keluar</th>
                        <th scope="col">Jam Lembur</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $att)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $att->record_date->format('d M Y') }}</td>
                            <td>{{ $att->check_in ? substr($att->check_in, 0, 5) : '-' }}</td>
                            <td>{{ $att->check_out ? substr($att->check_out, 0, 5) : '-' }}</td>
                            <td>
                                @if($att->overtime_hours > 0)
                                    <span class="badge bg-warning text-dark">{{ $att->overtime_hours }} Jam</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($att->status === 'Hadir')
                                    <span class="badge bg-success">Hadir</span>
                                @elseif($att->status === 'Alpa')
                                    <span class="badge bg-danger">Alpa</span>
                                @else
                                    <span class="badge bg-secondary">Cuti/Izin</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>