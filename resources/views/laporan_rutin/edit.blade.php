@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit Laporan Perawatan {{ $report->vehicle->tipe }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('laporan-rutin.index') }}">Laporan Perawatan</a></div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Form Laporan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan-rutin.update', $report) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('laporan_rutin._form', ['report' => $report])
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

