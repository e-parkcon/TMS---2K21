<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify OTP</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.0.0/mdb.min.css" rel="stylesheet"/>

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
        <div class="row justify-content-center">
            <div class="col-md-6">
                @if($errors->count() > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error) {{ $error }} @endforeach
                    </div>
                @endif
                
                @if(session()->has('Message'))
                    <div class="alert alert-info">
                        {{ session()->get('Message') }}
                    </div>
                @endif

                <form action="{{ route('vertifyOTP') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <span class="fa fa-lock"></span> <small>Verify OTP</small>
                        </div>

                        <div class="card-body">
                            <div class="col-md-12">
                                <label for="OTP"><small>One Time Password (OTP) has been sent to your mobile. Please enter the your OTP here to login.</small></label>
                            </div>
                            <br>
                            <div class="form-outline mb-4">
                                <input type="text" name="OTP" id="OTP" class="form-control" />
                                <label class="form-label" for="OTP"><small>Enter OTP </small></label>
                            </div>

                            <div class="row g-3 mb-2">

                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary btn-block">Verify OTP</button>
                                </div>

                                <div class="col-md-4">
                                    <a class="btn btn-link btn-block" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                        {{ __('Cancel') }}
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div>
                </form>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</body>

<!-- MDB -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.0.0/mdb.min.js"></script>

</html>