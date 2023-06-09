<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
{{--    <title>--}}
{{--        @yield('title_prefix', config('adminlte.title_prefix', ''))--}}
{{--        @yield('title', config('adminlte.title', 'AdminLTE 3'))--}}
{{--        @yield('title_postfix', config('adminlte.title_postfix', ''))--}}
{{--    </title>--}}

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')

    {{-- Base Stylesheets --}}

    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    @if(config('adminlte.google_fonts.allowed', true))
        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    @endif

    {{-- Extra Configured Plugins Stylesheets --}}
    @include('adminlte::plugins', ['type' => 'css'])

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')
    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}"/>
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="@yield('classes_body')" @yield('body_data')>

{{-- Base Scripts --}}
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Extra Configured Plugins Scripts --}}
@include('adminlte::plugins', ['type' => 'js'])
{{-- Custom Scripts --}}
@yield('adminlte_js')
@inertia

</body>
</html>
