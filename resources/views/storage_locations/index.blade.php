@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Lokasi Penyimpanan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item active">Storage Location</div>
            </div>
        </div>

        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>Daftar Lokasi</h4>
                    <div class="card-header-form">
                        <form method="GET" action="{{ route('storage-location.index') }}" class="form-inline">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="Cari kode/nama..."
                                    value="{{ request('q') }}">
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
                                    <th>Parent</th>
                                    <th style="width:140px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($locations as $loc)
                                    <tr>
                                        <td>{{ ($locations->currentPage() - 1) * $locations->perPage() + $loop->iteration }}</td>
                                        <td>{{ $loc->kode }}</td>
                                        <td>{{ $loc->nama }}</td>
                                        <td>{{ $loc->parent?->kode ? ($loc->parent->kode . ' — ' . $loc->parent->nama) : '-' }}</td>
                                        <td>
                                            <a href="{{ route('storage-location.edit', $loc) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('storage-location.destroy', $loc) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus lokasi ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" type="submit"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <a href="{{ route('storage-location.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
                    {{ $locations->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection

