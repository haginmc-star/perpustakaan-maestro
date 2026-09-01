@extends('layouts.guest')
@section('title', 'Login Admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="section-eyebrow text-center">Akses Terbatas</div>
        <div class="login-card p-4">
            <h4 class="mb-3">Login Admin</h4>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button class="btn btn-brass w-100">Masuk</button>
            </form>
        </div>
    </div>
</div>
@endsection
