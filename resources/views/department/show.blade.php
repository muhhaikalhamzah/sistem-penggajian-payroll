<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a href="{{ route('department.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('department.edit', $department) }}" class="btn btn-warning">Edit</a>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px">ID</th>
                <td>{{ $department->id }}</td>
            </tr>
            <tr>
                <th>Nama Departemen</th>
                <td>{{ $department->name }}</td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{{ $department->description }}</td>
            </tr>
            <tr>
                <th>Dibuat Pada</th>
                <td>{{ $department->created_at->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <th>Terakhir Diubah</th>
                <td>{{ $department->updated_at->format('d M Y H:i') }}</td>
            </tr>
        </table>
    </div>
</x-app>