@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit Kendaraan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('ranpur.index') }}">Ranpur</a></div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Form Kendaraan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('ranpur.update', $vehicle) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('ranpur._form', ['vehicle' => $vehicle])
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
