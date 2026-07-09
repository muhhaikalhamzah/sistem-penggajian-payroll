<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('position.create') }}" role="button">Tambah</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Jabatan</th>
                        <th scope="col">Gaji Min</th>
                        <th scope="col">Gaji Max</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($positions as $position)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $position->title }}</td>
                            <td>Rp {{ number_format($position->min_salary, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($position->max_salary, 0, ',', '.') }}</td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('position.show', $position) }}" class="btn btn-info btn-sm">
                                    <i class='bx bx-info-circle'></i>
                                </a>
                                <a href="{{ route('position.edit', $position) }}" class="btn btn-warning btn-sm">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-route="{{ route('position.destroy', $position) }}">
                                    <i class='bx bx-trash'></i>
                                </button>
                                </div>
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
