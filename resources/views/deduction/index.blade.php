<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('deduction.create') }}" role="button">Tambah</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Karyawan (NIK)</th>
                        <th scope="col">Nama Potongan</th>
                        <th scope="col">Tipe</th>
                        <th scope="col">Jumlah (Rp)</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deductions as $deduction)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $deduction->employee->first_name }} {{ $deduction->employee->last_name }} ({{ $deduction->employee->employee_number }})</td>
                            <td>{{ $deduction->name }}</td>
                            <td>
                                @if($deduction->type === 'Fixed')
                                    <span class="badge bg-primary">Fixed</span>
                                @else
                                    <span class="badge bg-success">Variable</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($deduction->amount, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('deduction.show', $deduction) }}" class="btn btn-info btn-sm">
                                    <i class='bx bx-info-circle'></i>
                                </a>
                                <a href="{{ route('deduction.edit', $deduction) }}" class="btn btn-warning btn-sm">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-route="{{ route('deduction.destroy', $deduction) }}">
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