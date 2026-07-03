<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('deduction.update', $deduction) }}" method="post" class="form">
            @csrf
            @method('put')
            <div class="mb-3">
                <label for="employee_id" class="form-label required">Karyawan</label>
                <select class="form-select select2-default @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                    <option value="">Pilih Karyawan</option>
                    @foreach ($employees as $emp)
                        <option value="{{ $emp->id }}" @selected(old('employee_id', $deduction->employee_id) == $emp->id)>{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_number }})</option>
                    @endforeach
                </select>
                @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="name" class="form-label required">Nama Potongan</label>
                <input class="form-control @error('name') is-invalid @enderror" type="text" id="name"
                    name="name" required value="{{ old('name', $deduction->name) }}">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="type" class="form-label required">Tipe</label>
                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                    <option value="">Pilih Tipe</option>
                    <option value="Tetap" @selected(old('type', $deduction->type) == 'Tetap')>Tetap (Fixed)</option>
                    <option value="Variabel" @selected(old('type', $deduction->type) == 'Variabel')>Variabel (Variable)</option>
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label required">Jumlah / Nominal (Rp)</label>
                <input class="form-control rupiah-input @error('amount') is-invalid @enderror" type="text" id="amount"
                    name="amount" required value="{{ old('amount', round($deduction->amount)) }}">
                @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="text-end">
                <a href="{{ route('deduction.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>