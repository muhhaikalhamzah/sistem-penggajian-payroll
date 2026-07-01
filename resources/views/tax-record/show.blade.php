<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a href="{{ route('tax-records.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px">ID Record</th>
                <td>{{ $tax->id }}</td>
            </tr>
            <tr>
                <th>Nama Karyawan</th>
                <td>{{ $tax->employee->first_name }} {{ $tax->employee->last_name }} ({{ $tax->employee->employee_number }})</td>
            </tr>
            <tr>
                <th>Status PTKP</th>
                <td>{{ $tax->employee->ptkp_status }}</td>
            </tr>
            <tr>
                <th>Periode Pajak</th>
                <td>{{ $tax->period }}</td>
            </tr>
            <tr>
                <th>Penghasilan Kena Pajak (PKP)</th>
                <td>Rp {{ number_format($tax->taxable_income, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Jumlah Pemotongan PPh 21</th>
                <td class="text-danger fw-bold">Rp {{ number_format($tax->pph21_amount, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Dibuat Pada</th>
                <td>{{ $tax->created_at->format('d M Y H:i') }}</td>
            </tr>
        </table>
    </div>
</x-app>
