@php
    $editing = isset($location);
@endphp

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="kode">Kode</label>
            <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror"
                value="{{ old('kode', $editing ? $location->kode : '') }}" placeholder="Contoh: GUD-1/RK-A/BIN-03" required>
            @error('kode')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                value="{{ old('nama', $editing ? $location->nama : '') }}" placeholder="Contoh: Gudang Utama / Rak A" required>
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="parent_id">Parent (opsional)</label>
            <select name="parent_id" id="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                <option value="">Tidak ada</option>
                @foreach ($parents as $p)
                    <option value="{{ $p->id }}" {{ (string) old('parent_id', $editing ? $location->parent_id : '') === (string) $p->id ? 'selected' : '' }}>
                        {{ $p->kode }} — {{ $p->nama }}
                    </option>
                @endforeach
            </select>
            @error('parent_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="text-right">
    <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
    <a href="{{ route('storage-location.index') }}" class="btn btn-secondary">Batal</a>
</div>

