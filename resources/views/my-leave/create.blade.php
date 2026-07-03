<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card shadow-lg p-3">
        <form action="{{ route('my-leaves.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="leave_type" class="form-label">Tipe Cuti</label>
                <select name="leave_type" id="leave_type" class="form-select" required>
                    <option value="Tahunan" {{ old('leave_type') == 'Tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                    <option value="Sakit" {{ old('leave_type') == 'Sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                    <option value="Lainnya" {{ old('leave_type') == 'Lainnya' ? 'selected' : '' }}>Lainnya / Di Luar Tanggungan</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}" required>
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">Tanggal Berakhir</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajukan</button>
            <a href="{{ route('my-leaves.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</x-app>