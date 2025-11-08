@php
    $editing = isset($movement);
@endphp

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="sparepart_id">Sparepart</label>
            <select name="sparepart_id" id="sparepart_id" class="form-control @error('sparepart_id') is-invalid @enderror" required>
                <option value="">Pilih sparepart</option>
                @foreach ($spareparts as $sp)
                    <option value="{{ $sp->id }}" {{ (string) old('sparepart_id', $editing ? $movement->sparepart_id : '') === (string) $sp->id ? 'selected' : '' }}>
                        {{ $sp->kode }} — {{ $sp->nama }}
                    </option>
                @endforeach
            </select>
            @error('sparepart_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="jenis">Jenis</label>
            @php $jenis = old('jenis', $editing ? $movement->jenis : 'IN'); @endphp
            <select name="jenis" id="jenis" class="form-control @error('jenis') is-invalid @enderror" required>
                <option value="IN" {{ $jenis==='IN'?'selected':'' }}>IN</option>
                <option value="OUT" {{ $jenis==='OUT'?'selected':'' }}>OUT</option>
            </select>
            @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="qty">Qty</label>
            <input type="number" min="1" name="qty" id="qty" class="form-control @error('qty') is-invalid @enderror" value="{{ old('qty', $editing ? $movement->qty : 1) }}" required>
            @error('qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="datetime-local" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $editing ? $movement->tanggal?->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" required>
            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="reference_id">Referensi (Sparepart Request Item) - opsional</label>
            <select name="reference_id" id="reference_id" class="form-control">
                <option value="">Tidak ada</option>
                @foreach ($requestItems as $it)
                    @php $label = sprintf('#%d %s %s (%s)', $it->id, $it->sparepart->kode, $it->sparepart->nama, $it->request->diagnosis->damageReport->vehicle->nomor_lambung); @endphp
                    <option value="{{ $it->id }}" {{ (string) old('reference_id', $editing && $movement->reference_type===App\Models\SparepartRequestItem::class ? $movement->reference_id : '') === (string) $it->id ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <input type="hidden" name="reference_type" value="App\\Models\\SparepartRequestItem">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="2" class="form-control">{{ old('keterangan', $editing ? $movement->keterangan : '') }}</textarea>
        </div>
    </div>
</div>

<div class="text-right">
    <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
    <a href="{{ route('stock-movement.index') }}" class="btn btn-secondary">Batal</a>
    </div>

