@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Detail Laporan Perawatan {{ $report->vehicle->tipe }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('laporan-rutin.index') }}">Laporan Perawatan</a></div>
                <div class="breadcrumb-item active">Detail</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Informasi Laporan</h4>
                    <div class="card-header-action">
                        <a href="{{ route('laporan-rutin.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                        @can('laporan-rutin.edit')
                            <a href="{{ route('laporan-rutin.edit', $report) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" class="form-control" value="{{ $report->tanggal->format('d M Y') }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pelapor</label>
                                <input type="text" class="form-control" value="{{ $report->reporter?->name ?? '-' }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kendaraan</label>
                                <input type="text" class="form-control" value="{{ $report->vehicle->nomor_lambung }} - {{ $report->vehicle->tipe }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kondisi Umum</label>
                                @php
                                    $badge = $report->cond_overall === 'BAIK' ? 'success' : ($report->cond_overall === 'CUKUP' ? 'warning' : 'danger');
                                @endphp
                                <div>
                                    <span class="badge badge-{{ $badge }}">{{ $report->cond_overall }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Item Pengecekan</label>
                                <div class="border rounded p-2" style="min-height: 42px;">
                                    @if($report->check_items && count($report->check_items))
                                        {{ implode(', ', $report->check_items) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Temuan Kerusakan</label>
                                <div>
                                    @if($report->ada_temuan_kerusakan)
                                        <span class="badge badge-danger">Ada</span>
                                    @else
                                        <span class="badge badge-success">Tidak</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Catatan</label>
                                <textarea class="form-control" rows="4" disabled>{{ $report->catatan }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

