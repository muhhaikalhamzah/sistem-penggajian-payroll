<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('allowance.create') }}" role="button">Tambah</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Karyawan (NIK)</th>
                        <th scope="col">Nama Tunjangan</th>
                        <th scope="col">Tipe</th>
                        <th scope="col">Jumlah (Rp)</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allowances as $allowance)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $allowance->employee->first_name }} {{ $allowance->employee->last_name }} ({{ $allowance->employee->employee_number }})</td>
                            <td>{{ $allowance->name }}</td>
                            <td>
                                @if($allowance->type === 'Fixed')
                                    <span class="badge bg-primary">Fixed</span>
                                @else
                                    <span class="badge bg-success">Variable</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($allowance->amount, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('allowance.show', $allowance) }}" class="btn btn-info btn-sm">
                                    <i class='bx bx-info-circle'></i>
                                </a>
                                <a href="{{ route('allowance.edit', $allowance) }}" class="btn btn-warning btn-sm">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-route="{{ route('allowance.destroy', $allowance) }}">
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