<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Karyawan (NIK)</th>
                        <th scope="col">Periode</th>
                        <th scope="col">Penghasilan Kena Pajak</th>
                        <th scope="col">Potongan PPh 21</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($taxRecords as $tax)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $tax->employee->first_name }} {{ $tax->employee->last_name }} ({{ $tax->employee->employee_number }})</td>
                            <td>{{ $tax->period }}</td>
                            <td>Rp {{ number_format($tax->taxable_income, 2, ',', '.') }}</td>
                            <td>Rp {{ number_format($tax->pph21_amount, 2, ',', '.') }}</td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('tax-records.show', $tax) }}" class="btn btn-info btn-sm">
                                        <i class='bx bx-info-circle'></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>
