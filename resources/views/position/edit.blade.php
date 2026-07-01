<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('position.update', $position) }}" method="post" class="form">
            @csrf
            @method('put')
            <div class="mb-3">
                <label for="title" class="form-label required">Nama Jabatan</label>
                <input class="form-control @error('title') is-invalid @enderror" type="text" id="title"
                    name="title" required value="{{ old('title', $position->title) }}">
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="min_salary" class="form-label required">Gaji Minimum</label>
                <input class="form-control @error('min_salary') is-invalid @enderror" type="number" id="min_salary"
                    name="min_salary" required value="{{ old('min_salary', $position->min_salary) }}" min="0">
                @error('min_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="max_salary" class="form-label required">Gaji Maksimum</label>
                <input class="form-control @error('max_salary') is-invalid @enderror" type="number" id="max_salary"
                    name="max_salary" required value="{{ old('max_salary', $position->max_salary) }}" min="0">
                @error('max_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="text-end">
                <a href="{{ route('position.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>
