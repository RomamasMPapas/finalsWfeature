@extends('master')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Forgot Password</h4>
                </div>
                <div class="card-body">
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    <p>Enter your email address and we will send you a link to reset your password.</p>
                    <form action="/forgot-password" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
