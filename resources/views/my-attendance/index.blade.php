<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
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
                            <td>{{ $att->check_in ? date('H:i', strtotime($att->check_in)) : '-' }}</td>
                            <td>{{ $att->check_out ? date('H:i', strtotime($att->check_out)) : '-' }}</td>
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