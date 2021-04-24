<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>TMS</title>

    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/admin-dashboard/jquery/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.0.0/mdb.min.css" rel="stylesheet"/>


    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('css/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">
    
    <style>
        body{
            background-color: #F9F9F9;
        }
        .container{
            left: 50%;
            position: fixed;
            top: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div class="col-md-4">

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="row">
                                <div class="col d-flex justify-content-center mb-2">
                                    <label for="form-label"><small>Time Management System</small></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col d-flex justify-content-center mb-3">
                                    <img src="{{ asset('images/user.png')}}" alt="User Avatar" class="img-fluid shadow-1-strong" style="width: 100px;border: 2px solid #eee;border-radius: 65px;">
                                </div>
                            </div>

                            <div class="form-outline mt-2 mb-2">
                                <input id="empno" type="empno" class="form-control @error('empno') is-invalid @enderror" name="empno" value="{{ old('empno') }}" required autocomplete="empno" autofocus>
                                <label class="form-label" for="empno"><small>Employee ID </small></label>
                                {{-- @error('empno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror --}}
                            </div>

                            <div class="form-outline mb-3">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                <label for="password" class="form-label"><small>Password </small></label>
                                {{-- @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror --}}
                            </div>
    
                            {{-- <div class="row mb-4">
                                <div class="col d-flex justify-content-end">
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link mt-1" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div> --}}
                            <br>
                            <button type="submit" class="btn btn-block btn-primary">
                                {{ __('Login') }}
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
</body>

<!-- MDB -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.0.0/mdb.min.js"></script>

<!-- SweetAlert2 -->
<script src="{{ asset('js/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('toastr/toastr.min.js') }}"></script>

@error('empno')
    <script>
        toastr.error("{{ $message }}")
    </script>
@enderror

@error('password')
    <script>
        toastr.error("{{ $message }}")
    </script>
@enderror

</html>
