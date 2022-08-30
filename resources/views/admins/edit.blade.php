@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Tambah Admin</h2>

    <form action="/admin/admins/{{ $user->id }}" method="POST">
        @method('put')
        @csrf
        <input type="hidden" name="password" value="{{ $user->password }}">
        <input type="hidden" name="token" value="{{ $user->remember_token }}">
        <input type="hidden" name="oldUsername" value="{{ $user->username }}">
        <input type="hidden" name="oldEmail" value="{{ $user->email }}">
        <div class="mb-3 mt-3">
            <label for="name" class="form-label @error('name') is-invalid @enderror">Nama Admin</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Nama"
                value="{{ old('name', $user->name) }}" required autofocus>
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3 mt-3">
            <label for="email" class="form-label @error('email') is-invalid @enderror">Email Admin</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Email"
                value="{{ old('email', $user->email) }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3 mt-3">
            <label for="username" class="form-label @error('username') is-invalid @enderror">Username Admin (Tanpa
                Spasi)</label>
            <input type="text" class="form-control" name="username" id="username" placeholder="Username"
                value="{{ old('username', $user->username) }}" required autofocus>
            @error('username')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="text-end">
            <a href="/admin/admins" class="btn btn-danger btn-sm mt-3">Kembali</a>
            <button type="submit" class="btn btn-primary btn-sm mt-3">Submit</button>
        </div>
    </form>
@endsection
