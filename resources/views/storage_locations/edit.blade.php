@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit Lokasi Penyimpanan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('storage-location.index') }}">Storage Location</a></div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Form Lokasi</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('storage-location.update', $location) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('storage_locations._form', ['location' => $location])
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

