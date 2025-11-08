@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit Pergerakan Stok</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('stock-movement.index') }}">Stock Movement</a></div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Form Pergerakan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('stock-movement.update', $movement) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('stock_movements._form', ['movement' => $movement])
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

