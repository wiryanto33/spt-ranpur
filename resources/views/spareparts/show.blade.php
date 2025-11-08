@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Detail Sparepart</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('sparepart.index') }}">Sparepart</a></div>
                <div class="breadcrumb-item active">Detail</div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Informasi Sparepart</h4>
                    <div class="card-header-action">
                        <a href="{{ route('sparepart.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                        @can('sparepart.edit')
                            <a href="{{ route('sparepart.edit', $sparepart) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kode</label>
                                <input type="text" class="form-control" value="{{ $sparepart->kode }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" value="{{ $sparepart->nama }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Stok</label>
                                <input type="text" class="form-control" value="{{ $sparepart->stok }} (min {{ $sparepart->stok_minimum }})" disabled>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Lokasi</label>
                                <input type="text" class="form-control" value="{{ $sparepart->location?->kode ? ($sparepart->location->kode . ' - ' . $sparepart->location->nama) : '-' }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Gambar</label>
                        <div>
                            @if($sparepart->image)
                                <img src="{{ asset('storage/' . $sparepart->image) }}" alt="img" style="max-height:200px">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

