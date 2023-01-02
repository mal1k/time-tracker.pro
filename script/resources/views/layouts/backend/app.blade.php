<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
     <link rel="apple-touch-icon" href="{{ asset('pwa/152_152.png')  }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="white"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ env('APP_URL')  }}">
    <meta name="msapplication-TileImage" content="{{ asset('pwa/128_128.png')  }}">
    <meta name="msapplication-TileColor" content="#FFFFFF">
   
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>

    <link rel="icon" href="{{ asset('uploads/favicon.ico') }}">
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/chocolat.css') }}">
    @stack('beforestyle.css')

    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/style.css?v=1.1.1') }}">
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/components.css') }}">
    @stack('css')

   
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <!--- Header Section ---->
      @include('layouts.backend.partials.header')

      <!--- Sidebar Section --->
      @include('layouts.backend.partials.sidebar')

      <!--- Main Content --->
      <div class="main-content  main-wrapper-1">
        <section class="section">
         @yield('head')
        </section>
        @yield('content')
      </div>

     <!--- Footer Section --->
     @include('layouts.backend.partials.footer')
    </div>
  </div>

  <input type="hidden" class="base_url" value="{{ url('/') }}">
  <!-- General JS Scripts -->
  <script src="{{ asset('frontend/assets/js/jquery-3.6.0.min.js') }}" ></script>
  <script src="{{ asset('backend/admin/assets/js/popper.min.js') }}" ></script>
  <script src="{{ asset('backend/admin/assets/js/bootstrap.min.js') }}" ></script>
  <script src="{{ asset('backend/admin/assets/js/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('backend/admin/assets/js/moment.min.js') }}"></script>
  <script src="{{ asset('backend/admin/assets/js/sweetalert2.all.min.js') }}"></script>
  <!-- Template JS File -->
  <script src="{{ asset('backend/admin/assets/js/chocolat.js') }}"></script>
  <!-- Page Specific JS File -->
  @stack('js')
  <script src="{{ asset('backend/admin/assets/js/scripts.js') }}"></script>
  <script src="{{ asset('backend/admin/assets/js/custom.js') }}"></script>
  <script src="{{ asset('backend/admin/assets/js/form.js') }}"></script>
  @if(Auth()->user()->role_id == 2)
  <script src="{{ asset('backend/admin/assets/js/notification.js') }}"></script>
  @endif
  
</body>
</html>
