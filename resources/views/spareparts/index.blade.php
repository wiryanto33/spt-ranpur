@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Sparepart</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item active">Sparepart</div>
            </div>
        </div>

        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>Daftar Sparepart</h4>
                    <div class="card-header-form">
                        <form method="GET" action="{{ route('sparepart.index') }}" class="form-inline">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="Cari kode/nama..." value="{{ request('q') }}">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Stok</th>
                                    <th>Lokasi</th>
                                    <th>Gambar</th>
                                    <th style="width:180px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($spareparts as $sp)
                                    <tr>
                                        <td>{{ ($spareparts->currentPage() - 1) * $spareparts->perPage() + $loop->iteration }}</td>
                                        <td>{{ $sp->kode }}</td>
                                        <td>{{ $sp->nama }}</td>
                                        <td>{{ $sp->stok }} <small class="text-muted">/ min {{ $sp->stok_minimum }}</small></td>
                                        <td>{{ $sp->location?->kode ? ($sp->location->kode . ' — ' . $sp->location->nama) : '-' }}</td>
                                        <td>
                                            @if($sp->image)
                                                @php $src = preg_match('/^uploads\//', $sp->image) ? asset($sp->image) : asset('storage/'.$sp->image); @endphp
                                                <img src="{{ $src }}" alt="img" style="max-height:50px">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @can('sparepart.show')
                                                <a href="{{ route('sparepart.show', $sp) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                            @endcan
                                            @can('sparepart.edit')
                                                <a href="{{ route('sparepart.edit', $sp) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            @endcan
                                            @can('sparepart.destroy')
                                                <form action="{{ route('sparepart.destroy', $sp) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus sparepart ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" type="submit"><i class="fas fa-trash"></i></button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @can('sparepart.create')
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <a href="{{ route('sparepart.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
                        {{ $spareparts->links() }}
                    </div>
                @else
                    <div class="card-footer d-flex justify-content-end align-items-center">
                        {{ $spareparts->links() }}
                    </div>
                @endcan
            </div>
        </div>
    </section>
@endsection
