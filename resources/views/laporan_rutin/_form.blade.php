@php
    $editing = isset($report);
    $items = ['Sistem Kekedapan', 'Sistem Suspensi', 'Sistem Keselamatan', 'Sistem Pendorong', 'Sistem Listrik', 'Sistem Senjata', 'Alat Kelengkapan'];
    $selectedItems = collect(old('check_items', $editing ? $report->check_items : []))->toArray();
    $canSelectVehicle = auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('mechanic');
    $userVehicle = auth()->user()->vehicle()->first();
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="ranpur_id">Kendaraan</label>
            @if ($canSelectVehicle)
                <select name="ranpur_id" id="ranpur_id" class="form-control @error('ranpur_id') is-invalid @enderror" required>
                    <option value="">Pilih Kendaraan</option>
                    @foreach ($vehicles as $v)
                        <option value="{{ $v->id }}" {{ old('ranpur_id', $editing ? $report->ranpur_id : '') == $v->id ? 'selected' : '' }}>
                            {{ $v->nomor_lambung }} - {{$v->tipe}}
                        </option>
                    @endforeach
                </select>
            @else
                @if($userVehicle)
                    <input type="text" class="form-control" value="{{ $userVehicle->nomor_lambung }} - {{ $userVehicle->tipe }}" disabled>
                    <input type="hidden" name="ranpur_id" value="{{ $userVehicle->id }}">
                @else
                    <input type="text" class="form-control" value="Tidak ada ranpur ditugaskan" disabled>
                @endif
            @endif
            @error('ranpur_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                value="{{ old('tanggal', $editing ? $report->tanggal?->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
            @error('tanggal')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="tipe">Tipe Laporan</label>
            <select name="tipe" id="tipe" class="form-control @error('tipe') is-invalid @enderror" required>
                @php $tipe = old('tipe', $editing ? $report->tipe : 'RUTIN'); @endphp
                <option value="RUTIN" {{ $tipe === 'RUTIN' ? 'selected' : '' }}>RUTIN</option>
            </select>
            @error('tipe')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="cond_overall">Kondisi Umum</label>
            <select name="cond_overall" id="cond_overall" class="form-control @error('cond_overall') is-invalid @enderror" required>
                @php $cond = old('cond_overall', $editing ? $report->cond_overall : 'BAIK'); @endphp
                <option value="BAIK" {{ $cond === 'BAIK' ? 'selected' : '' }}>BAIK</option>
                <option value="CUKUP" {{ $cond === 'CUKUP' ? 'selected' : '' }}>CUKUP</option>
                <option value="BURUK" {{ $cond === 'BURUK' ? 'selected' : '' }}>BURUK</option>
            </select>
            @error('cond_overall')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            <label>Item Pengecekan</label>
            <div class="row">
                @foreach ($items as $item)
                    <div class="col-md-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" id="item_{{ $item }}" name="check_items[]" value="{{ $item }}" class="custom-control-input"
                                {{ in_array($item, $selectedItems) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="item_{{ $item }}">{{ $item }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('check_items')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <div class="custom-control custom-switch mt-4">
                @php $temuan = old('ada_temuan_kerusakan', $editing ? (int) $report->ada_temuan_kerusakan : 0); @endphp
                <input type="checkbox" class="custom-control-input" id="ada_temuan_kerusakan" name="ada_temuan_kerusakan" value="1" {{ $temuan ? 'checked' : '' }}>
                <label class="custom-control-label" for="ada_temuan_kerusakan">Ada Temuan Kerusakan</label>
            </div>
            @error('ada_temuan_kerusakan')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            <label for="catatan">Catatan</label>
            <textarea name="catatan" id="catatan" rows="3" class="form-control @error('catatan') is-invalid @enderror"
                placeholder="Opsional">{{ old('catatan', $editing ? $report->catatan : '') }}</textarea>
            @error('catatan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="text-right">
    <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
    <a href="{{ route('laporan-rutin.index') }}" class="btn btn-secondary">Batal</a>
    </div>
