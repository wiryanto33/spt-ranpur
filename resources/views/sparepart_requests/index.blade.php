@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Permintaan Sparepart</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item active">Sparepart Request</div>
            </div>
        </div>
        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Permintaan</h4>
                    <div class="card-header-form">
                        <form method="GET" action="{{ route('sparepart-request.index') }}" class="form-inline">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control"
                                    placeholder="Cari status/catatan..." value="{{ request('q') }}">
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
                                    <th>Ranpur</th>
                                    <th>Judul Kerusakan</th>
                                    <th>Status</th>
                                    <th>Diminta oleh</th>
                                    <th style="width:180px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($requests as $row)
                                    <tr>
                                        <td>{{ ($requests->currentPage() - 1) * $requests->perPage() + $loop->iteration }}
                                        </td>
                                        <td>{{ $row->tanggal->format('d M Y') }}</td>
                                        <td>{{ $row->diagnosis->damageReport->vehicle->nomor_lambung }} -
                                            {{ $row->diagnosis->damageReport->vehicle->tipe }}</td>
                                        <td>{{ $row->diagnosis->damageReport->judul }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'DIAJUKAN' => 'badge-warning',
                                                    'DISETUJUI' => 'badge-success',
                                                    'DITOLAK' => 'badge-danger',
                                                    'SEBAGIAN' => 'badge-info',
                                                    'SELESAI' => 'badge-primary',
                                                ];
                                                $statusClass = $statusColors[$row->status] ?? 'badge-secondary';
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ $row->status }}</span>
                                        </td>
                                        <td>{{ $row->requester->name }}</td>
                                        <td>
                                            @can('sparepart-request.show')
                                                <a href="{{ route('sparepart-request.show', $row) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                            @endcan
                                            @can('sparepart-request.edit')
                                                <a href="{{ route('sparepart-request.edit', $row) }}"
                                                    class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            @endcan
                                            @can('sparepart-request.destroy')
                                                <form action="{{ route('sparepart-request.destroy', $row) }}" method="POST"
                                                    class="d-inline" onsubmit="return confirm('Hapus permintaan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" type="submit"><i
                                                            class="fas fa-trash"></i></button>
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
                @can('sparepart-request.create')
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <a href="{{ route('sparepart-request.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                            Ajukan</a>
                        {{ $requests->links() }}
                    </div>
                @else
                    <div class="card-footer d-flex justify-content-end align-items-center">
                        {{ $requests->links() }}
                    </div>
                @endcan
            </div>
        </div>
    </section>
@endsection
