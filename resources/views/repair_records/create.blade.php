@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Catatan Perbaikan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('repair-record.index') }}">Repair Record</a></div>
                <div class="breadcrumb-item active">Tambah</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Form Perbaikan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('repair-record.store') }}" method="POST">
                        @csrf
                        @include('repair_records._form')
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

