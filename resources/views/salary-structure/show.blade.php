<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a href="{{ route('salary-structure.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('salary-structure.edit', $salaryStructure) }}" class="btn btn-warning">Edit</a>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px">ID</th>
                <td>{{ $salaryStructure->id }}</td>
            </tr>
            <tr>
                <th>Karyawan (NIK)</th>
                <td>{{ $salaryStructure->employee->first_name }} {{ $salaryStructure->employee->last_name }} ({{ $salaryStructure->employee->employee_number }})</td>
            </tr>
            <tr>
                <th>Gaji Pokok</th>
                <td>Rp {{ number_format($salaryStructure->basic_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Tanggal Efektif Berlaku</th>
                <td>{{ $salaryStructure->effective_date->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>Dibuat Pada</th>
                <td>{{ $salaryStructure->created_at->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <th>Terakhir Diubah</th>
                <td>{{ $salaryStructure->updated_at->format('d M Y H:i') }}</td>
            </tr>
        </table>
    </div>
</x-app>
