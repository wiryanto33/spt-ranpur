@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Laporan Perawatan Kendaraan Rutin</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item active">Laporan Perawatan</div>
            </div>
        </div>

        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>Daftar Laporan</h4>
                    <div class="card-header-form">
                        <form method="GET" action="{{ route('laporan-rutin.index') }}" class="form-inline">
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
                                <input type="text" name="q" class="form-control" placeholder="Cari kendaraan..."
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
                                    <th>Tanggal</th>
                                    <th>Kendaraan</th>
                                    <th>Kondisi</th>
                                    <th>Item</th>
                                    <th>Temuan</th>
                                    <th style="width:180px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports as $report)
                                    <tr>
                                        <td>{{ ($reports->currentPage() - 1) * $reports->perPage() + $loop->iteration }}</td>
                                        <td>{{ $report->tanggal->format('d M Y') }}</td>
                                        <td>{{ $report->vehicle->nomor_lambung }} - {{ $report->vehicle->tipe }}</td>
                                        <td><span class="badge badge-{{ $report->cond_overall === 'BAIK' ? 'success' : ($report->cond_overall === 'CUKUP' ? 'warning' : 'danger') }}">{{ $report->cond_overall }}</span></td>
                                        <td>{{ $report->check_items ? implode(', ', $report->check_items) : '-' }}</td>
                                        <td>
                                            @if ($report->ada_temuan_kerusakan)
                                                <span class="badge badge-danger">Ada</span>
                                            @else
                                                <span class="badge badge-success">Tidak</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can('laporan-rutin.show')
                                                <a href="{{ route('laporan-rutin.show', $report) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                            @endcan
                                            @can('laporan-rutin.edit')
                                                <a href="{{ route('laporan-rutin.edit', $report) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            @endcan
                                            @can('laporan-rutin.destroy')
                                                <form action="{{ route('laporan-rutin.destroy', $report) }}" method="POST"
                                                    class="d-inline" onsubmit="return confirm('Hapus laporan ini?');">
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
                @can('laporan-rutin.create')
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <a href="{{ route('laporan-rutin.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
                        {{ $reports->links() }}
                    </div>
                @else
                    <div class="card-footer d-flex justify-content-end align-items-center">
                        {{ $reports->links() }}
                    </div>
                @endcan
            </div>
        </div>
    </section>
@endsection
