<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('attendance.store') }}" method="post" class="form">
            @csrf
            <div class="mb-3">
                <label for="employee_id" class="form-label required">Karyawan</label>
                <select class="form-select select2-default @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                    <option value="">Pilih Karyawan</option>
                    @foreach ($employees as $emp)
                        <option value="{{ $emp->id }}" @selected(old('employee_id') == $emp->id)>{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_number }})</option>
                    @endforeach
                </select>
                @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="record_date" class="form-label required">Tanggal</label>
                <input class="form-control @error('record_date') is-invalid @enderror" type="date" id="record_date"
                    name="record_date" required value="{{ old('record_date') ?: date('Y-m-d') }}">
                @error('record_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="check_in" class="form-label">Jam Masuk</label>
                    <input class="form-control @error('check_in') is-invalid @enderror" type="time" id="check_in"
                        name="check_in" value="{{ old('check_in') }}">
                    <small class="text-muted">Kosongkan jika tidak hadir</small>
                    @error('check_in') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="check_out" class="form-label">Jam Keluar</label>
                    <input class="form-control @error('check_out') is-invalid @enderror" type="time" id="check_out"
                        name="check_out" value="{{ old('check_out') }}">
                    <small class="text-muted">Overtime dihitung jika lewat pukul 17:00</small>
                    @error('check_out') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label required">Status Kehadiran</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="Present" @selected(old('status') == 'Present')>Hadir (Present)</option>
                    <option value="Absent" @selected(old('status') == 'Absent')>Alpa (Absent)</option>
                    <option value="Leave" @selected(old('status') == 'Leave')>Cuti/Izin (Leave)</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="text-end">
                <a href="{{ route('attendance.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>