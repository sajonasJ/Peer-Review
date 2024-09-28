@extends('layouts.master')

@section('title', 'Teaching Team Login')

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
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Teaching Team Login</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('teacher,login') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="sNumber">Staff Number</label>
                                <input type="text" name="sNumber" class="form-control" placeholder="Enter your Staff Number" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </div>
                        </form>

                        <!-- Link for back to student login -->
                        <div class="text-center mt-3">
                            <p>Student login? <a href="/" class="text-primary">Go to Student Login</a></p>
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
