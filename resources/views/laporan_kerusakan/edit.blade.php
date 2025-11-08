@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit Laporan Kerusakan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('laporan-kerusakan.index') }}">Laporan Kerusakan</a></div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Form Laporan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan-kerusakan.update', $report) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('laporan_kerusakan._form', ['report' => $report])
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
