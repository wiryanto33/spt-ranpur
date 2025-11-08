@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Detail Laporan Pemeriksaan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('diagnosis-report.index') }}">laporan Pemeriksaan</a></div>
                <div class="breadcrumb-item active">Detail</div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Informasi Laporan</h4>
                    <div class="card-header-action">
                        <a href="{{ route('diagnosis-report.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                        @can('diagnosis-report.edit')
                            <a href="{{ route('diagnosis-report.edit', $report) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
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
                                <input type="text" class="form-control" value="{{ $report->damageReport->vehicle->nomor_lambung }} - {{ $report->damageReport->vehicle->tipe }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Mekanik</label>
                                <input type="text" class="form-control" value="{{ $report->mechanic?->name ?? '-' }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kerusakan</label>
                                <input type="text" class="form-control" value="{{ $report->damageReport->judul }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Temuan</label>
                                <textarea class="form-control" rows="4" disabled>{{ $report->temuan }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Rencana Tindakan</label>
                        <textarea class="form-control" rows="4" disabled>{{ $report->rencana_tindakan }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

