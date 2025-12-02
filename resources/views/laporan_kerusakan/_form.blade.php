@php
    $editing = isset($report);
    $statusOptions = ['DILAPORKAN', 'DIPERIKSA', 'PROSES_PERBAIKAN', 'SELESAI'];
    $canSetStatus = auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('mechanic');
    $canSelectVehicle = $canSetStatus; // same roles
    $userVehicle = auth()->user()->vehicle()->first();
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="ranpur_id">Kendaraan</label>
            @if ($canSelectVehicle)
                <select name="ranpur_id" id="ranpur_id" class="form-control @error('ranpur_id') is-invalid @enderror"
                    required>
                    <option value="">Pilih Kendaraan</option>
                    @foreach ($vehicles as $v)
                        <option value="{{ $v->id }}"
                            {{ old('ranpur_id', $editing ? $report->ranpur_id : '') == $v->id ? 'selected' : '' }}>
                            {{ $v->nomor_lambung }} - {{ $v->tipe }}
                        </option>
                    @endforeach
                </select>
            @else
                @if ($userVehicle)
                    <input type="text" class="form-control"
                        value="{{ $userVehicle->nomor_lambung }} - {{ $userVehicle->tipe }}" disabled>
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
            <input type="date" name="tanggal" id="tanggal"
                class="form-control @error('tanggal') is-invalid @enderror"
                value="{{ old('tanggal', $editing ? $report->tanggal?->format('Y-m-d') : now()->format('Y-m-d')) }}"
                required>
            @error('tanggal')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="status">Status</label>
            @php $status = old('status', $editing ? $report->status : 'DILAPORKAN'); @endphp
            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required
                {{ $canSetStatus ? '' : 'disabled' }}>
                @foreach ($statusOptions as $opt)
                    <option value="{{ $opt }}" {{ $status === $opt ? 'selected' : '' }}>{{ $opt }}
                    </option>
                @endforeach
            </select>
            @if (!$canSetStatus)
                <input type="hidden" name="status" value="{{ $editing ? $status : 'DILAPORKAN' }}">
            @endif
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="judul">Judul</label>
            <input type="text" name="judul" id="judul"
                class="form-control @error('judul') is-invalid @enderror" placeholder="Ringkasan kerusakan"
                value="{{ old('judul', $editing ? $report->judul : '') }}" required>
            @error('judul')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="20" class="form-control @error('deskripsi') is-invalid @enderror"
                placeholder="Detail kerusakan (opsional)">{{ old('deskripsi', $editing ? $report->deskripsi : '') }}</textarea>
            @error('deskripsi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="text-right">
    <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
    <a href="{{ route('laporan-kerusakan.index') }}" class="btn btn-secondary">Batal</a>
</div>
