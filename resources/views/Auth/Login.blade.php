@extends('layouts.app')

@section('content')
    <div class="container mt-5" style="max-width: 500px">
        <h2 class="mb-4 text-center">Login</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group mb-3">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>

            <div class="form-group mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
@endsection
