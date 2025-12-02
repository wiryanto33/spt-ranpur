@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit Permintaan Sparepart</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('sparepart-request.index') }}">Sparepart Request</a></div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Form Permintaan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('sparepart-request.update', $requestHeader) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="diagnosis_report_id">Laporan Pemeriksaan</label>
                                    <select name="diagnosis_report_id" id="diagnosis_report_id" class="form-control @error('diagnosis_report_id') is-invalid @enderror" required>
                                        <option value="">Pilih Diagnosis</option>
                                        @foreach ($diagnoses as $d)
                                            <option value="{{ $d->id }}" {{ old('diagnosis_report_id', $requestHeader->diagnosis_report_id) == $d->id ? 'selected' : '' }}>
                                                {{ $d->tanggal->format('d M Y') }} — {{ $d->damageReport->vehicle->nomor_lambung }} — {{ $d->damageReport->judul }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('diagnosis_report_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $requestHeader->tanggal->format('Y-m-d')) }}" class="form-control @error('tanggal') is-invalid @enderror" required>
                                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    @php
                                        $status = old('status', $requestHeader->status);
                                        $canSetStatus = auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('staff');
                                    @endphp
                                    @if($canSetStatus)
                                        <select name="status" id="status" class="form-control">
                                            @foreach (['DIAJUKAN','DISETUJUI','DITOLAK','SEBAGIAN','SELESAI'] as $opt)
                                                <option value="{{ $opt }}" {{ $status === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" class="form-control" value="{{ $status }}" disabled>
                                        <input type="hidden" name="status" value="{{ $status }}">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="approved_by">Disetujui oleh</label>
                                    @if($canSetStatus)
                                        @if(auth()->user()->hasRole('staff'))
                                            @php
                                                $approvedById = old('approved_by', $requestHeader->approved_by);
                                                if ($approvedById) {
                                                    $displayName = optional($requestHeader->approver)->name;
                                                    if (empty($displayName)) {
                                                        $displayName = optional(\App\Models\User::find($approvedById))->name;
                                                    }
                                                } else {
                                                    $displayName = auth()->user()->name;
                                                    $approvedById = auth()->id();
                                                }
                                            @endphp
                                            <input type="text" class="form-control" value="{{ $displayName }}" disabled>
                                            <input type="hidden" name="approved_by" id="approved_by" value="{{ $approvedById }}">
                                        @else
                                            <input type="number" class="form-control" name="approved_by" id="approved_by" value="{{ old('approved_by', $requestHeader->approved_by) }}" placeholder="User ID (opsional)">
                                        @endif
                                    @else
                                        <input type="text" class="form-control" value="{{ $requestHeader->approved_by ?? '-' }}" disabled>
                                        <input type="hidden" name="approved_by" value="{{ $requestHeader->approved_by }}">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <textarea name="catatan" id="catatan" rows="2" class="form-control">{{ old('catatan', $requestHeader->catatan) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Item</label>
                            <div class="table-responsive">
                                <table class="table" id="items-table">
                                    <thead>
                                        <tr>
                                            <th>Sparepart</th>
                                            <th style="width:140px">Qty diminta</th>
                                            <th style="width:160px">Qty disetujui</th>
                                            <th style="width:180px">Status</th>
                                            <th style="width:60px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $idx=0; @endphp
                                        @foreach ($requestHeader->items as $it)
                                            <tr>
                                                <td>
                                                    <select name="items[{{ $idx }}][sparepart_id]" class="form-control" required>
                                                        <option value="">Pilih sparepart</option>
                                                        @foreach ($spareparts as $sp)
                                                            <option value="{{ $sp->id }}" {{ $it->sparepart_id == $sp->id ? 'selected' : '' }}>{{ $sp->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" min="1" name="items[{{ $idx }}][qty_diminta]" class="form-control" value="{{ $it->qty_diminta }}" required>
                                                </td>
                                                <td>
                                                    <input type="number" min="0" name="items[{{ $idx }}][qty_disetujui]" class="form-control" value="{{ $it->qty_disetujui }}">
                                                </td>
                                                <td>
                                                    @php $s = $it->status_item; @endphp
                                                    <select name="items[{{ $idx }}][status_item]" class="form-control">
                                                        @foreach (['DIAJUKAN','DISETUJUI','DITOLAK','DIPENUHI_SEBAGIAN','SELESAI'] as $opt)
                                                            <option value="{{ $opt }}" {{ $s === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fas fa-times"></i></button>
                                                </td>
                                            </tr>
                                            @php $idx++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-info" id="btn-add-row"><i class="fas fa-plus"></i> Tambah Item</button>
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
        let idx = $('#items-table tbody tr').length;
        function addRow() {
            let tpl = `@include('sparepart_requests._items_row')`;
            tpl = tpl.replaceAll('IDX', idx++);
            $('#items-table tbody').append(tpl);
        }
        $('#btn-add-row').on('click', addRow);
        $(document).on('click', '.btn-remove-row', function(){ $(this).closest('tr').remove(); });
    });
</script>
@endpush
