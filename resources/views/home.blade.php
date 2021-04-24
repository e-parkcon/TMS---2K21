@extends('layouts.app')

@section('content')
{{-- <div class="row"> --}}
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Hi! You are logged in!
                </div>
            </div>
        </div>
    </div>
{{-- </div> --}}
@endsection
