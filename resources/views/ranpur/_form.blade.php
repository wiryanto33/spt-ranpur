@php
    $editing = isset($vehicle);
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="nomor_lambung">Nomor Lambung</label>
            <input type="text" name="nomor_lambung" id="nomor_lambung"
                class="form-control @error('nomor_lambung') is-invalid @enderror"
                value="{{ old('nomor_lambung', $editing ? $vehicle->nomor_lambung : '') }}" placeholder="Contoh: 302"
                required>
            @error('nomor_lambung')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="tipe">Tipe</label>
            <input type="text" name="tipe" id="tipe" class="form-control @error('tipe') is-invalid @enderror"
                value="{{ old('tipe', $editing ? $vehicle->tipe : '') }}" placeholder="Contoh: Tank PT-76" required>
            @error('tipe')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="satuan">Kompi</label>
            <input type="text" name="satuan" id="satuan"
                class="form-control @error('satuan') is-invalid @enderror"
                value="{{ old('satuan', $editing ? $vehicle->satuan : '') }}" placeholder="Contoh: Yon Tank Marinir">
            @error('satuan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="tahun">Tahun</label>
            <input type="text" name="tahun" id="tahun"
                class="form-control @error('tahun') is-invalid @enderror"
                value="{{ old('tahun', $editing ? $vehicle->tahun : '') }}" placeholder="YYYY">
            @error('tahun')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="status_kesiapan">Status Kesiapan</label>
            <select name="status_kesiapan" id="status_kesiapan"
                class="form-control @error('status_kesiapan') is-invalid @enderror" required>
                @php
                    $status = old('status_kesiapan', $editing ? $vehicle->status_kesiapan : 'SIAP LAUT');
                @endphp
                <option value="SIAP LAUT" {{ $status === 'SIAP LAUT' ? 'selected' : '' }}>SIAP LAUT</option>
                <option value="SIAP DARAT" {{ $status === 'SIAP DARAT' ? 'selected' : '' }}>SIAP DARAT</option>
                <option value="TIDAK SIAP" {{ $status === 'TIDAK SIAP' ? 'selected' : '' }}>TIDAK SIAP</option>
            </select>
            @error('status_kesiapan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="form-group">
    <label for="keterangan">Keterangan</label>
    <textarea name="keterangan" id="keterangan" rows="3"
        class="form-control @error('keterangan') is-invalid @enderror" placeholder="Opsional">{{ old('keterangan', $editing ? $vehicle->keterangan : '') }}</textarea>
    @error('keterangan')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="mt-3 text-right">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        <a href="{{ route('ranpur.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</div>
