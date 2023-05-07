<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="{{asset('css/main-style.css')}}">
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
        <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    </head>
    <body class="font-sans antialiased">
        @inertia

        @env ('local')
            <script src="{{asset('js/bundle.js')}}"></script>
        @endenv
        <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('js/main-script.js')}}"></script>
    </body>
</html>



