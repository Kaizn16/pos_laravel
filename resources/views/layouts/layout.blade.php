<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!----======== CSS ======== -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">
         
    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">

    <!----===== JS ===== -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/chart.js') }}"></script>

    <title>Sari-Sari Store P.O.S System</title> 
</head>
<body>
    @include('layouts.nav')
    <div class="container">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
            <strong id="real-time-date">Date</strong>
            <div class="profile">
                <strong>Administrator{{-- {{ Auth::user()->name }} --}}</strong>
                <img src="{{ asset('assets/Images/rem.png') }}">
            </div>
        </div>
        
        {{-- PAGE CONTENT GOES HERE --}}
        @yield('content')
         {{-- PAGE CONTENT GOES HERE --}}

    </div>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2-11.js') }}"></script>
</body>
</html>