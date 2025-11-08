@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Sparepart</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('sparepart.index') }}">Sparepart</a></div>
                <div class="breadcrumb-item active">Tambah</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Form Sparepart</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('sparepart.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('spareparts._form')
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

