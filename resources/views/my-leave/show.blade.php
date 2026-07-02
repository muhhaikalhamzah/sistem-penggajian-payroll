<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a href="{{ route('my-leaves.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px">ID Cuti</th>
                <td>{{ $leave->id }}</td>
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
                <td>
                    @if($leave->status == 'Menunggu')
                        <span class="badge bg-warning text-dark">Menunggu</span>
                    @elseif($leave->status == 'Disetujui')
                        <span class="badge bg-success">Disetujui</span>
                    @else
                        <span class="badge bg-danger">Ditolak</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Diajukan Pada</th>
                <td>{{ $leave->created_at->format('d M Y H:i') }}</td>
            </tr>
        </table>
    </div>
</x-app>