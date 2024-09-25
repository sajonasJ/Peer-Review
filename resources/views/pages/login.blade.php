@extends('layouts.master')

@section('title', 'Login')

@section('header')
    @include('layouts.header')
@endsection

@section('nav')
    @include('layouts.nav')
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white text-center">
                        <h4>Login to Your Account</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email"
                                    >
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Enter your password" >
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-danger btn-block">Login</button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <p>Not yet registered? <a href="{{ route('register') }}" class="text-danger">Create an
                                    account</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
