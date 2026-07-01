<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('salary-structure.create') }}" role="button">Tambah</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Karyawan (NIK)</th>
                        <th scope="col">Gaji Pokok</th>
                        <th scope="col">Berlaku Sejak</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salaryStructures as $salary)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $salary->employee->first_name }} {{ $salary->employee->last_name }} ({{ $salary->employee->employee_number }})</td>
                            <td>Rp {{ number_format($salary->basic_salary, 0, ',', '.') }}</td>
                            <td>{{ $salary->effective_date->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('salary-structure.show', $salary) }}" class="btn btn-info btn-sm">
                                    <i class='bx bx-info-circle'></i>
                                </a>
                                <a href="{{ route('salary-structure.edit', $salary) }}" class="btn btn-warning btn-sm">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-route="{{ route('salary-structure.destroy', $salary) }}">
                                    <i class='bx bx-trash'></i>
                                </button>
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
