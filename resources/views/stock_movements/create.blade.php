@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Pergerakan Stok</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('stock-movement.index') }}">Stock Movement</a></div>
                <div class="breadcrumb-item active">Tambah</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Form Pergerakan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('stock-movement.store') }}" method="POST">
                        @csrf
                        @include('stock_movements._form')
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

