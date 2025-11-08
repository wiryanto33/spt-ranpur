@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Catatan Perbaikan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item active">Repair Record</div>
            </div>
        </div>

        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>Daftar Perbaikan</h4>
                    <div class="card-header-form">
                        <form method="GET" action="{{ route('repair-record.index') }}" class="form-inline">
                            <div class="input-group mr-2">
                                <select name="ranpur_id" class="form-control">
                                    <option value="">Semua Kendaraan</option>
                                    @foreach ($vehicles as $v)
                                        <option value="{{ $v->id }}" {{ request('ranpur_id') == $v->id ? 'selected' : '' }}>
                                            {{ $v->nomor_lambung }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="Cari hasil/uraian..."
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
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Kendaraan</th>
                                    <th>Kerusakan</th>
                                    <th>Hasil</th>
                                    <th style="width:180px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($records as $row)
                                    <tr>
                                        <td>{{ ($records->currentPage() - 1) * $records->perPage() + $loop->iteration }}</td>
                                        <td>{{ $row->mulai->format('d M Y H:i') }}</td>
                                        <td>{{ $row->selesai ? $row->selesai->format('d M Y H:i') : '-' }}</td>
                                        <td>{{ $row->vehicle->nomor_lambung }}</td>
                                        <td>{{ $row->damageReport?->judul ?? '-' }}</td>
                                        <td><span class="badge badge-{{ $row->hasil==='SIAP' ? 'success' : 'danger' }}">{{ $row->hasil }}</span></td>
                                        <td>
                                            @can('repair-record.show')
                                                <a href="{{ route('repair-record.show', $row) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                            @endcan
                                            @can('repair-record.edit')
                                                <a href="{{ route('repair-record.edit', $row) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            @endcan
                                            @can('repair-record.destroy')
                                                <form action="{{ route('repair-record.destroy', $row) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus record ini?');">
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
                @can('repair-record.create')
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <a href="{{ route('repair-record.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
                        {{ $records->links() }}
                    </div>
                @else
                    <div class="card-footer d-flex justify-content-end align-items-center">
                        {{ $records->links() }}
                    </div>
                @endcan
            </div>
        </div>
    </section>
@endsection
