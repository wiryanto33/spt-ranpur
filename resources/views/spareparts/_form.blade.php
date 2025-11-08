@php $editing = isset($sparepart); @endphp

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="kode">Kode</label>
            <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode', $editing ? $sparepart->kode : '') }}" required>
            @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $editing ? $sparepart->nama : '') }}" required>
            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="satuan">Satuan</label>
            <input type="text" name="satuan" id="satuan" class="form-control @error('satuan') is-invalid @enderror" value="{{ old('satuan', $editing ? $sparepart->satuan : 'pcs') }}" required>
            @error('satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="stok">Stok</label>
            <input type="number" min="0" name="stok" id="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', $editing ? $sparepart->stok : 0) }}" required>
            @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="stok_minimum">Stok Minimum</label>
            <input type="number" min="0" name="stok_minimum" id="stok_minimum" class="form-control @error('stok_minimum') is-invalid @enderror" value="{{ old('stok_minimum', $editing ? $sparepart->stok_minimum : 0) }}" required>
            @error('stok_minimum')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="storage_location_id">Lokasi Penyimpanan</label>
            <select name="storage_location_id" id="storage_location_id" class="form-control @error('storage_location_id') is-invalid @enderror">
                <option value="">Pilih lokasi</option>
                @foreach ($locations as $loc)
                    <option value="{{ $loc->id }}" {{ (string) old('storage_location_id', $editing ? $sparepart->storage_location_id : '') === (string) $loc->id ? 'selected' : '' }}>
                        {{ $loc->kode }} — {{ $loc->nama }}
                    </option>
                @endforeach
            </select>
            @error('storage_location_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="image">Gambar</label>
            <input type="file" name="image" id="image" class="form-control-file @error('image') is-invalid @enderror" accept="image/*">
            @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
            @if($editing && $sparepart->image)
                @php $src = preg_match('/^uploads\//', $sparepart->image) ? asset($sparepart->image) : asset('storage/'.$sparepart->image); @endphp
                <div class="mt-2"><img src="{{ $src }}" alt="preview" style="max-height:80px"></div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Opsional">{{ old('keterangan', $editing ? $sparepart->keterangan : '') }}</textarea>
            @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="text-right">
    <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
    <a href="{{ route('sparepart.index') }}" class="btn btn-secondary">Batal</a>
</div>
