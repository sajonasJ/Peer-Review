@extends('layouts.master')

@section('title', 'Login')

@section('header')
    @include('layouts.header')
@endsection

{{-- @section('nav')
    @include('layouts.nav')
@endsection --}}

@section('content')
    <div class="container-fluid m-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header cs-red text-white text-center">
                        <h4>Student Login</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('student.login') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="snumber">Student Number</label>
                                <input type="text" name="snumber" class="form-control" placeholder="Enter your sNumber">
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Enter your password">
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-danger btn-block">Login</button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <p>Not yet registered? <a href="{{ route('register') }}"
                                    class="text-danger 
                                    link-offset-0 
                                    link-offset-1-hover 
                                    link-danger
                                    link-underline 
                                    link-underline-opacity-0 
                                    link-underline-opacity-75-hover">Create
                                    an
                                    account</a></p>
                        </div>
                        <div class="text-center mt-3">
                            <p>Switch to: <a href="{{ route('teaching-login') }}"
                                    class="text-danger 
                                    link-offset-0 
                                    link-offset-1-hover 
                                    link-danger
                                    link-underline 
                                    link-underline-opacity-0 
                                    link-underline-opacity-75-hover">Staff Login
                                </a></p>
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
