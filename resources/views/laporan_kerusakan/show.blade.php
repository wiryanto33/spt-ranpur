@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Detail Laporan Kerusakan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('laporan-kerusakan.index') }}">Laporan Kerusakan</a></div>
                <div class="breadcrumb-item active">Detail</div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Informasi Laporan</h4>
                    <div class="card-header-action">
                        <a href="{{ route('laporan-kerusakan.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                        @can('laporan-kerusakan.edit')
                            <a href="{{ route('laporan-kerusakan.edit', $report) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" class="form-control" value="{{ $report->tanggal->format('d M Y') }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kendaraan</label>
                                <input type="text" class="form-control" value="{{ $report->vehicle->nomor_lambung }} - {{ $report->vehicle->tipe }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pelapor</label>
                                <input type="text" class="form-control" value="{{ $report->reporter?->name ?? '-' }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" class="form-control" value="{{ $report->judul }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                @php
                                    $badge = match($report->status) {
                                        'SELESAI' => 'success',
                                        'PROSES_PERBAIKAN' => 'warning',
                                        'DIPERIKSA' => 'info',
                                        default => 'secondary'
                                    };
                                @endphp
                                <div><span class="badge badge-{{ $badge }}">{{ $report->status }}</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" rows="5" disabled>{{ $report->deskripsi }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
