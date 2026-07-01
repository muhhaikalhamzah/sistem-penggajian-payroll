<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a href="{{ route('deduction.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('deduction.edit', $deduction) }}" class="btn btn-warning">Edit</a>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px">ID</th>
                <td>{{ $deduction->id }}</td>
            </tr>
            <tr>
                <th>Karyawan (NIK)</th>
                <td>{{ $deduction->employee->first_name }} {{ $deduction->employee->last_name }} ({{ $deduction->employee->employee_number }})</td>
            </tr>
            <tr>
                <th>Nama Potongan</th>
                <td>{{ $deduction->name }}</td>
            </tr>
            <tr>
                <th>Tipe</th>
                <td>
                    @if($deduction->type === 'Fixed')
                        <span class="badge bg-primary">Fixed</span>
                    @else
                        <span class="badge bg-success">Variable</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Jumlah (Rp)</th>
                <td>Rp {{ number_format($deduction->amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Dibuat Pada</th>
                <td>{{ $deduction->created_at->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <th>Terakhir Diubah</th>
                <td>{{ $deduction->updated_at->format('d M Y H:i') }}</td>
            </tr>
        </table>
    </div>
</x-app>