@extends('layouts.app')

@section('content')
        <section class="section">
            <div class="section-header">
                <h1>Edit User</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('user.index') }}">Users</a></div>
                    <div class="breadcrumb-item">Edit User</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <form action="{{ route('user.update', $user) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-header">
                            <h4>User Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>NRP</label>
                                        <input type="text" class="form-control @error('nrp') is-invalid @enderror"
                                            name="nrp" value="{{ old('nrp', $user->nrp) }}">
                                        @error('nrp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Pangkat</label>
                                        <input type="text" class="form-control @error('pangkat') is-invalid @enderror"
                                            name="pangkat" value="{{ old('pangkat', $user->pangkat) }}">
                                        @error('pangkat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Jabatan</label>
                                        <input type="text" class="form-control @error('jabatan') is-invalid @enderror"
                                            name="jabatan" value="{{ old('jabatan', $user->jabatan) }}">
                                        @error('jabatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Foto Profil</label>
                                        <input type="file" class="form-control-file @error('image') is-invalid @enderror" name="image" accept="image/*">
                                        @if($user->image)
                                            @php
                                                $img = $user->image;
                                                $src = $img ? (preg_match('/^uploads\//', $img) ? asset($img) : asset('storage/'.$img)) : null;
                                            @endphp
                                            @if($src)
                                                <div class="mt-2"><img src="{{ $src }}" alt="preview" style="max-height:80px"></div>
                                            @endif
                                        @endif
                                        @error('image')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password">
                                        <small class="form-text text-muted">Leave blank if you don't want to change the
                                            password.</small>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Password Confirmation</label>
                                        <input type="password" class="form-control" name="password_confirmation">
                                    </div>
                                    <div class="form-group">
                                        <label>Role</label>
                                        <select class="form-control select2 @error('role') is-invalid @enderror"
                                            name="role" required>
                                            @foreach ($roles as $id => $name)
                                                <option value="{{ $name }}"
                                                    {{ $user->hasRole($name) ? 'selected' : '' }}>{{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control @error('status') is-invalid @enderror" name="status"
                                            required>
                                            <option value="AKTIF" {{ $user->status == 'AKTIF' ? 'selected' : '' }}>AKTIF
                                            </option>
                                            <option value="TIDAK_AKTIF"
                                                {{ $user->status == 'TIDAK_AKTIF' ? 'selected' : '' }}>TIDAK AKTIF</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Assigned Ranpur</label>
                                        <select class="form-control select2 @error('ranpur_id') is-invalid @enderror"
                                            name="ranpur_id">
                                            <option value="">-- No Ranpur --</option>
                                            @foreach ($vehicles as $vehicle)
                                                <option value="{{ $vehicle->id }}"
                                                    {{ $user->ranpur_id == $vehicle->id ? 'selected' : '' }}>
                                                    {{ $vehicle->nomor_lambung }} ({{ $vehicle->tipe }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('ranpur_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
@endpush
