@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Detail Catatan Perbaikan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('repair-record.index') }}">Repair Record</a></div>
                <div class="breadcrumb-item active">Detail</div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Informasi Perbaikan</h4>
                    <div class="card-header-action">
                        <a href="{{ route('repair-record.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                        @can('repair-record.edit')
                            <a href="{{ route('repair-record.edit', $record) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Mulai</label>
                                <input type="text" class="form-control" value="{{ $record->mulai->format('d M Y H:i') }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Selesai</label>
                                <input type="text" class="form-control" value="{{ $record->selesai ? $record->selesai->format('d M Y H:i') : '-' }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Mekanik</label>
                                <input type="text" class="form-control" value="{{ $record->mechanic?->name ?? '-' }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kendaraan</label>
                                <input type="text" class="form-control" value="{{ $record->vehicle->nomor_lambung }} - {{ $record->vehicle->tipe }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kerusakan</label>
                                <input type="text" class="form-control" value="{{ $record->damageReport?->judul ?? '-' }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Uraian Pekerjaan</label>
                                <textarea class="form-control" rows="5" disabled>{{ $record->uraian_pekerjaan }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Hasil</label>
                                <div>
                                    <span class="badge badge-{{ $record->hasil==='SIAP' ? 'success' : 'danger' }}">{{ $record->hasil }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

