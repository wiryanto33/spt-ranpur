@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Ajukan Permintaan Sparepart</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('sparepart-request.index') }}">Sparepart Request</a></div>
                <div class="breadcrumb-item active">Ajukan</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Form Permintaan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('sparepart-request.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="diagnosis_report_id">Laporan Pemeriksaan</label>
                                    <select name="diagnosis_report_id" id="diagnosis_report_id" class="form-control @error('diagnosis_report_id') is-invalid @enderror" required>
                                        <option value="">Pilih Diagnosis</option>
                                        @foreach ($diagnoses as $d)
                                            <option value="{{ $d->id }}">{{ $d->tanggal->format('d M Y') }} — {{ $d->damageReport->vehicle->nomor_lambung }} — {{ $d->damageReport->judul }}</option>
                                        @endforeach
                                    </select>
                                    @error('diagnosis_report_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" class="form-control @error('tanggal') is-invalid @enderror" required>
                                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    @php $canSetStatus = auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('staff'); @endphp
                                    @if($canSetStatus)
                                        <select name="status" id="status" class="form-control">
                                            <option value="DIAJUKAN">DIAJUKAN</option>
                                            <option value="DISETUJUI">DISETUJUI</option>
                                            <option value="DITOLAK">DITOLAK</option>
                                            <option value="SEBAGIAN">SEBAGIAN</option>
                                            <option value="SELESAI">SELESAI</option>
                                        </select>
                                    @else
                                        <input type="text" class="form-control" value="DIAJUKAN" disabled>
                                        <input type="hidden" name="status" value="DIAJUKAN">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <textarea name="catatan" id="catatan" rows="2" class="form-control">{{ old('catatan') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Item</label>
                            <div class="table-responsive">
                                <table class="table" id="items-table">
                                    <thead>
                                        <tr>
                                            <th>Sparepart</th>
                                            <th style="width:160px">Qty diminta</th>
                                            <th style="width:60px"></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-info" id="btn-add-row"><i class="fas fa-plus"></i> Tambah Item</button>
                            @error('items') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="text-right">
                            <button class="btn btn-primary">Simpan</button>
                            <a href="{{ route('sparepart-request.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('customScript')
<script>
    $(function(){
        let idx = 0;
        function addRow() {
            let tpl = `@include('sparepart_requests._items_row')`;
            tpl = tpl.replaceAll('IDX', idx++);
            $('#items-table tbody').append(tpl);
        }
        $('#btn-add-row').on('click', addRow);
        $(document).on('click', '.btn-remove-row', function(){ $(this).closest('tr').remove(); });
        addRow();
    });
</script>
@endpush
