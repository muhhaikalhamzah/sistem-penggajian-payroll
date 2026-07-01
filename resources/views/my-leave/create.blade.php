<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="card shadow-lg p-3">
        <form action="{{ route('my-leaves.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="leave_type" class="form-label">Tipe Cuti</label>
                <select name="leave_type" id="leave_type" class="form-select" required>
                    <option value="Annual">Cuti Tahunan (Annual)</option>
                    <option value="Sick">Cuti Sakit (Sick)</option>
                    <option value="Unpaid">Cuti di Luar Tanggungan (Unpaid)</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">Tanggal Berakhir</label>
                <input type="date" name="end_date" id="end_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajukan</button>
            <a href="{{ route('my-leaves.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</x-app>