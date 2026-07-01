<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a href="{{ route('allowance.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('allowance.edit', $allowance) }}" class="btn btn-warning">Edit</a>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px">ID</th>
                <td>{{ $allowance->id }}</td>
            </tr>
            <tr>
                <th>Karyawan (NIK)</th>
                <td>{{ $allowance->employee->first_name }} {{ $allowance->employee->last_name }} ({{ $allowance->employee->employee_number }})</td>
            </tr>
            <tr>
                <th>Nama Tunjangan</th>
                <td>{{ $allowance->name }}</td>
            </tr>
            <tr>
                <th>Tipe</th>
                <td>
                    @if($allowance->type === 'Fixed')
                        <span class="badge bg-primary">Fixed</span>
                    @else
                        <span class="badge bg-success">Variable</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Jumlah (Rp)</th>
                <td>Rp {{ number_format($allowance->amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Dibuat Pada</th>
                <td>{{ $allowance->created_at->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <th>Terakhir Diubah</th>
                <td>{{ $allowance->updated_at->format('d M Y H:i') }}</td>
            </tr>
        </table>
    </div>
</x-app>