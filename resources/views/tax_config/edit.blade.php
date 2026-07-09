<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $title }}</h5>
                    
                    <form action="{{ route('tax-configs.update', $config->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" value="{{ $config->name }}" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tarif (%)</label>
                            <input type="number" step="0.01" class="form-control @error('rate_percentage') is-invalid @enderror" name="rate_percentage" value="{{ old('rate_percentage', $config->rate_percentage) }}" required>
                            @error('rate_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                                <option value="1" {{ old('is_active', $config->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', $config->is_active) == 0 ? 'selected' : '' }}>Non-aktif</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('tax-configs.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app>
