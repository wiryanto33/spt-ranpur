@php
    $editing = isset($report);
    $selectedComponents = collect(old('komponen_diganti', $editing ? $report->komponen_diganti : []))->toArray();
    $componentOptions = [
        'Sistem Kekedapan',
        'Sistem Suspensi',
        'Sistem Keselamatan',
        'Sistem Pendorong',
        'Sistem Listrik',
        'Sistem Senjata',
        'Alat Kelengkapan',
    ];
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="damage_report_id">Laporan Kerusakan</label>
            <select name="damage_report_id" id="damage_report_id"
                class="form-control @error('damage_report_id') is-invalid @enderror" required>
                <option value="">Pilih Laporan Kerusakan</option>
                @foreach ($damageReports as $dr)
                    <option value="{{ $dr->id }}"
                        {{ old('damage_report_id', $editing ? $report->damage_report_id : '') == $dr->id ? 'selected' : '' }}>
                        {{ $dr->tanggal->format('d M Y') }} — {{ $dr->vehicle->nomor_lambung }} — {{ $dr->judul }}
                    </option>
                @endforeach
            </select>
            @error('damage_report_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="tanggal">Tanggal Pemeriksaan</label>
            <input type="date" name="tanggal" id="tanggal"
                class="form-control @error('tanggal') is-invalid @enderror"
                value="{{ old('tanggal', $editing ? $report->tanggal?->format('Y-m-d') : now()->format('Y-m-d')) }}"
                required>
            @error('tanggal')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="temuan">Temuan</label>
            <textarea name="temuan" id="temuan" rows="3" class="form-control @error('temuan') is-invalid @enderror"
                placeholder="Ringkasan temuan">{{ old('temuan', $editing ? $report->temuan : '') }}</textarea>
            @error('temuan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>Komponen Diganti (opsional)</label>
            <div class="row">
                @foreach ($componentOptions as $opt)
                    <div class="col-md-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" id="comp_{{ $opt }}" name="komponen_diganti[]"
                                value="{{ $opt }}" class="custom-control-input"
                                {{ in_array($opt, $selectedComponents) ? 'checked' : '' }}>
                            <label class="custom-control-label"
                                for="comp_{{ $opt }}">{{ $opt }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('komponen_diganti')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="rencana_tindakan">Rencana Tindakan</label>
            <textarea name="rencana_tindakan" id="rencana_tindakan" rows="3"
                style="min-height:200px !important; height:auto !important; box-sizing:border-box;"
                class="form-control @error('rencana_tindakan') is-invalid @enderror" placeholder="Langkah lanjutan (opsional)">{{ old('rencana_tindakan', $editing ? $report->rencana_tindakan : '') }}</textarea>
            @error('rencana_tindakan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="text-right">
    <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
    <a href="{{ route('diagnosis-report.index') }}" class="btn btn-secondary">Batal</a>
</div>
