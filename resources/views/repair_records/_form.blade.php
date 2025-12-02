@php $editing = isset($record); @endphp

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="ranpur_id">Kendaraan</label>
            <select name="ranpur_id" id="ranpur_id" class="form-control @error('ranpur_id') is-invalid @enderror" required>
                <option value="">Pilih Kendaraan</option>
                @foreach ($vehicles as $v)
                    <option value="{{ $v->id }}"
                        {{ (string) old('ranpur_id', $editing ? $record->ranpur_id : '') === (string) $v->id ? 'selected' : '' }}>
                        {{ $v->nomor_lambung }}</option>
                @endforeach
            </select>
            @error('ranpur_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="damage_report_id">Laporan Kerusakan (opsional)</label>
            <select name="damage_report_id" id="damage_report_id"
                class="form-control @error('damage_report_id') is-invalid @enderror">
                <option value="">Tidak ada</option>
                @foreach ($damages as $d)
                    <option value="{{ $d->id }}"
                        {{ (string) old('damage_report_id', $editing ? $record->damage_report_id : '') === (string) $d->id ? 'selected' : '' }}>
                        {{ $d->tanggal->format('d M Y') }} — {{ $d->vehicle->nomor_lambung }} — {{ $d->judul }}
                    </option>
                @endforeach
            </select>
            @error('damage_report_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="mulai">Mulai</label>
            <input type="datetime-local" id="mulai" name="mulai"
                class="form-control @error('mulai') is-invalid @enderror"
                value="{{ old('mulai', $editing ? $record->mulai?->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                required>
            @error('mulai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="selesai">Selesai</label>
            <input type="datetime-local" id="selesai" name="selesai"
                class="form-control @error('selesai') is-invalid @enderror"
                value="{{ old('selesai', $editing && $record->selesai ? $record->selesai->format('Y-m-d\TH:i') : '') }}">
            @error('selesai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="hasil">Hasil</label>
            @php $hasil = old('hasil', $editing ? $record->hasil : 'SIAP'); @endphp
            <select name="hasil" id="hasil" class="form-control @error('hasil') is-invalid @enderror" required>
                <option value="SIAP" {{ $hasil === 'SIAP' ? 'selected' : '' }}>SIAP</option>
                <option value="TIDAK_SIAP" {{ $hasil === 'TIDAK_SIAP' ? 'selected' : '' }}>TIDAK_SIAP</option>
            </select>
            @error('hasil')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            <label for="uraian_pekerjaan">Uraian Pekerjaan</label>
            <textarea name="uraian_pekerjaan" id="uraian_pekerjaan" rows="3"
                style="min-height:200px !important; height:auto !important; box-sizing:border-box;"
                class="form-control @error('uraian_pekerjaan') is-invalid @enderror" placeholder="Opsional">{{ old('uraian_pekerjaan', $editing ? $record->uraian_pekerjaan : '') }}</textarea>
            @error('uraian_pekerjaan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="text-right">
    <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
    <a href="{{ route('repair-record.index') }}" class="btn btn-secondary">Batal</a>
</div>
