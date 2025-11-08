@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Detail Permintaan Sparepart</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('sparepart-request.index') }}">Sparepart Request</a></div>
                <div class="breadcrumb-item active">Detail</div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Informasi Permintaan</h4>
                    <div class="card-header-action">
                        <a href="{{ route('sparepart-request.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                        @can('sparepart-request.edit')
                            <a href="{{ route('sparepart-request.edit', $requestHeader) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" class="form-control" value="{{ $requestHeader->tanggal->format('d M Y') }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                @php
                                    $statusColors = [
                                        'DIAJUKAN' => 'badge-warning',
                                        'DISETUJUI' => 'badge-success',
                                        'DITOLAK' => 'badge-danger',
                                        'SEBAGIAN' => 'badge-info',
                                        'SELESAI' => 'badge-primary',
                                    ];
                                    $statusClass = $statusColors[$requestHeader->status] ?? 'badge-secondary';
                                @endphp
                                <div><span class="badge {{ $statusClass }}">{{ $requestHeader->status }}</span></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Diagnosis</label>
                                <input type="text" class="form-control"
                                    value="{{ $requestHeader->diagnosis->tanggal->format('d M Y') }} - {{ $requestHeader->diagnosis->damageReport->vehicle->nomor_lambung }} - {{ $requestHeader->diagnosis->damageReport->judul }}"
                                    disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Diminta Oleh</label>
                                <input type="text" class="form-control" value="{{ $requestHeader->requester?->name ?? '-' }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Disetujui Oleh</label>
                                <input type="text" class="form-control" value="{{ $requestHeader->approver?->name ?? '-' }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sparepart</th>
                                    <th>Qty Diminta</th>
                                    <th>Qty Disetujui</th>
                                    <th>Status Item</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requestHeader->items as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item->sparepart->kode }} - {{ $item->sparepart->nama }}</td>
                                        <td>{{ $item->qty_diminta }}</td>
                                        <td>{{ $item->qty_disetujui }}</td>
                                        <td>{{ $item->status_item }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

