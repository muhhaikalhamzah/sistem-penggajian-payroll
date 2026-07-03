<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('salary-structure.update', $salaryStructure) }}" method="post" class="form">
            @csrf
            @method('put')
            <div class="mb-3">
                <label for="employee_id" class="form-label required">Karyawan</label>
                <select class="form-select select2-default @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                    <option value="">Pilih Karyawan</option>
                    @foreach ($employees as $emp)
                        <option value="{{ $emp->id }}" @selected(old('employee_id', $salaryStructure->employee_id) == $emp->id)>{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_number }})</option>
                    @endforeach
                </select>
                @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="basic_salary" class="form-label required">Gaji Pokok</label>
                <input class="form-control rupiah-input @error('basic_salary') is-invalid @enderror" type="text" id="basic_salary"
                    name="basic_salary" required value="{{ old('basic_salary', round($salaryStructure->basic_salary)) }}">
                @error('basic_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="effective_date" class="form-label required">Tanggal Efektif Berlaku</label>
                <input class="form-control @error('effective_date') is-invalid @enderror" type="date" id="effective_date"
                    name="effective_date" required value="{{ old('effective_date', $salaryStructure->effective_date->format('Y-m-d')) }}">
                @error('effective_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="text-end">
                <a href="{{ route('salary-structure.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>
