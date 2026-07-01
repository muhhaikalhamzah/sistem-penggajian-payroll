<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a href="{{ route('position.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('position.edit', $position) }}" class="btn btn-warning">Edit</a>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px">ID</th>
                <td>{{ $position->id }}</td>
            </tr>
            <tr>
                <th>Nama Jabatan</th>
                <td>{{ $position->title }}</td>
            </tr>
            <tr>
                <th>Gaji Minimum</th>
                <td>Rp {{ number_format($position->min_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Gaji Maksimum</th>
                <td>Rp {{ number_format($position->max_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Dibuat Pada</th>
                <td>{{ $position->created_at->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <th>Terakhir Diubah</th>
                <td>{{ $position->updated_at->format('d M Y H:i') }}</td>
            </tr>
        </table>
    </div>
</x-app>