@extends('layouts.master')

@section('title', 'Register')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white text-center">
                        <h4>Create an Account</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">Full Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Create a password" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Re-type password" required>
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-danger btn-block">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Back to login link -->
                <div class="text-center mt-3">
                    <p>Already have an account? <a href="/" class="text-danger">Back to Login</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
