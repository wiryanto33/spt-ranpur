@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Pergerakan Stok</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item active">Stock Movement</div>
            </div>
        </div>

        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>Daftar Pergerakan</h4>
                    <div class="card-header-form">
                        <form method="GET" action="{{ route('stock-movement.index') }}" class="form-inline">
                            <div class="input-group mr-2">
                                <select name="sparepart_id" class="form-control">
                                    <option value="">Semua Sparepart</option>
                                    @foreach ($spareparts as $sp)
                                        <option value="{{ $sp->id }}" {{ request('sparepart_id') == $sp->id ? 'selected' : '' }}>{{ $sp->kode }} — {{ $sp->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="Cari jenis/keterangan..." value="{{ request('q') }}">
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
                                    <th>Tanggal</th>
                                    <th>Sparepart</th>
                                    <th>Jenis</th>
                                    <th>Qty</th>
                                    <th>Referensi</th>
                                    <th>Pelaksana</th>
                                    <th style="width:140px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($movements as $row)
                                    <tr>
                                        <td>{{ ($movements->currentPage() - 1) * $movements->perPage() + $loop->iteration }}</td>
                                        <td>{{ $row->tanggal->format('d M Y H:i') }}</td>
                                        <td>{{ $row->sparepart->kode }} — {{ $row->sparepart->nama }}</td>
                                        <td>
                                            <span class="badge badge-{{ $row->jenis === 'IN' ? 'success' : 'warning' }}">{{ $row->jenis }}</span>
                                        </td>
                                        <td>{{ $row->qty }}</td>
                                        <td>
                                            @if($row->reference_type)
                                                {{ class_basename($row->reference_type) }} #{{ $row->reference_id }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $row->performer?->name ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('stock-movement.edit', $row) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('stock-movement.destroy', $row) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pergerakan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" type="submit"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Belum ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <a href="{{ route('stock-movement.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
                    {{ $movements->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
