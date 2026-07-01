<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('employee.update', $employee) }}" method="post" class="form">
            @csrf
            @method('put')
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="employee_number" class="form-label required">NIK</label>
                    <input class="form-control @error('employee_number') is-invalid @enderror" type="text" id="employee_number" name="employee_number" required value="{{ old('employee_number', $employee->employee_number) }}">
                    @error('employee_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="join_date" class="form-label required">Tanggal Bergabung</label>
                    <input class="form-control @error('join_date') is-invalid @enderror" type="date" id="join_date" name="join_date" required value="{{ old('join_date', $employee->join_date->format('Y-m-d')) }}">
                    @error('join_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="first_name" class="form-label required">Nama Depan</label>
                    <input class="form-control @error('first_name') is-invalid @enderror" type="text" id="first_name" name="first_name" required value="{{ old('first_name', $employee->first_name) }}">
                    @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="last_name" class="form-label required">Nama Belakang</label>
                    <input class="form-control @error('last_name') is-invalid @enderror" type="text" id="last_name" name="last_name" required value="{{ old('last_name', $employee->last_name) }}">
                    @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="ptkp_status" class="form-label required">Status PTKP</label>
                    <input class="form-control @error('ptkp_status') is-invalid @enderror" type="text" id="ptkp_status" name="ptkp_status" required value="{{ old('ptkp_status', $employee->ptkp_status) }}">
                    @error('ptkp_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="department_id" class="form-label required">Departemen</label>
                    <select class="form-select @error('department_id') is-invalid @enderror" id="department_id" name="department_id" required>
                        <option value="">Pilih Departemen</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}" @selected(old('department_id', $employee->department_id) == $dept->id)>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="position_id" class="form-label required">Jabatan</label>
                    <select class="form-select @error('position_id') is-invalid @enderror" id="position_id" name="position_id" required>
                        <option value="">Pilih Jabatan</option>
                        @foreach ($positions as $pos)
                            <option value="{{ $pos->id }}" @selected(old('position_id', $employee->position_id) == $pos->id)>{{ $pos->title }}</option>
                        @endforeach
                    </select>
                    @error('position_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-12">
                    <label for="user_id" class="form-label">Akun User (Opsional)</label>
                    <select class="form-select select2-default @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                        <option value="">Tidak ada akun login</option>
                        @foreach ($users as $usr)
                            <option value="{{ $usr->id }}" @selected(old('user_id', $employee->user_id) == $usr->id)>{{ $usr->name }} ({{ $usr->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="text-end mt-3">
                <a href="{{ route('employee.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>
