@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Ranpur</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item active">Ranpur</div>
            </div>
        </div>

        <div class="section-body">
            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Daftar Kendaraan</h4>
                    <div class="card-header-form">
                        <form method="GET" action="{{ route('ranpur.index') }}" class="form-inline">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="Cari..."
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
                                    <th>Nomor Lambung</th>
                                    <th>Tipe</th>
                                    <th>Satuan/Kompi</th>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                    <th style="width: 140px">
                                        @if (auth()->user()->can('ranpur.edit') && auth()->user()->can('ranpur.destroy'))
                                            <div class="text-center">Action</div>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($vehicles as $vehicle)
                                    <tr>
                                        <td>{{ ($vehicles->currentPage() - 1) * $vehicles->perPage() + $loop->iteration }}
                                        </td>
                                        <td>{{ $vehicle->nomor_lambung }}</td>
                                        <td>{{ $vehicle->tipe }}</td>
                                        <td>{{ $vehicle->satuan ?? '-' }}</td>
                                        <td>{{ $vehicle->tahun ?? '-' }}</td>
                                        <td>

                                            {{-- @if ($vehicles->status_kesiapan == 'SIAP LAUT')
                                                <span class="badge badge-success">SIAP LAUT</span>
                                            @elseif ($vehicles->status_kesiapan == 'SIAP DARAT')
                                                <span class="badge badge-warning">SIAP DARAT</span>
                                            @else
                                                <span class="badge badge-danger">TIDAK SIAP</span>
                                            @endif --}}

                                            @php
                                                $badge =
                                                    $vehicle->status_kesiapan === 'SIAP LAUT'
                                                        ? 'success'
                                                        : ($vehicle->status_kesiapan === 'SIAP DARAT'
                                                            ? 'warning'
                                                            : 'danger');
                                            @endphp
                                            <span
                                                class="badge badge-{{ $badge }}">{{ $vehicle->status_kesiapan }}</span>
                                        </td>
                                        <td>
                                            @can('ranpur.edit')
                                                <a href="{{ route('ranpur.edit', $vehicle) }}"
                                                    class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            @endcan

                                            @can('ranpur.destroy')
                                                <form action="{{ route('ranpur.destroy', $vehicle) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE" />
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                    <button class="btn btn-sm btn-danger confirm-delete" type="submit"><i
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
                @can('ranpur.create')
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <a href="{{ route('ranpur.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
                        {{ $vehicles->links() }}
                    </div>
                @endcan

            </div>
        </div>
    </section>
@endsection
