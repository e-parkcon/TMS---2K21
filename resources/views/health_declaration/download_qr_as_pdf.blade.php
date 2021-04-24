<!DOCTYPE html>
<html>

<head>
    <title>QR Code</title>
    
    <style>
        .container {
            /* left: 50%;
            position: fixed;
            top: 50%;
            transform: translate(-50%, -50%); */
            /* top: 30%;
            left: 30%; */
            margin: 10%;
            width: 100%;
            /* position: fixed; */
            text-align: center;
        }
        /* .image{
            background-position: center;
        } */
    </style>
</head>
<body>
    
<div class="container">
    <img src="{{ $src }}" class="image">

    <h2 style="text-transform: uppercase; text-align: center;">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</h2>
</div>

</body>

</html>