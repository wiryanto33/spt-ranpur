@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit Sparepart</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('sparepart.index') }}">Sparepart</a></div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Form Sparepart</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('sparepart.update', $sparepart) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('spareparts._form', ['sparepart' => $sparepart])
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

