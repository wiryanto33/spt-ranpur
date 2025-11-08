@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit Catatan Perbaikan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('repair-record.index') }}">Repair Record</a></div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Form Perbaikan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('repair-record.update', $record) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('repair_records._form', ['record' => $record])
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

