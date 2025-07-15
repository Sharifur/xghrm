<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        @laravelPWA

            <link rel="stylesheet" href="{{ mix('css/app.css') }}">
            <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
            <link rel="stylesheet" href="{{asset('css/main-style.css')}}">
            <link rel="stylesheet" href="{{ asset('css/pwa-popup.css') }}">
        @if(request()->is(['admin','login']))
            <link rel="stylesheet" href="{{asset('css/loginscreen.css')}}">
        @endif
        @routes
        @vite([ 'resources/js/app.js'])
        @inertiaHead
{{--        <script src="{{ mix('js/app.js') }}" defer></script>--}}
        <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    </head>
    <body class="font-sans antialiased">
        @inertia




        <!-- PWA Install Popup -->
        <div id="pwa-install-popup" class="pwa-install-popup">
            <button class="popup-close" onclick="hideInstallPopup()">&times;</button>
            <div class="popup-content">
                <span class="popup-icon">📱</span>
                <div class="popup-title">Install XGHRM App</div>
                <div class="popup-description">
                    Get quick access to your HR system with our mobile app experience
                </div>

                <div class="popup-buttons">
                    <button class="btn-install" onclick="installApp()">
                        Install Now
                    </button>
                    <button class="btn-later" onclick="hideInstallPopup()">
                        Maybe Later
                    </button>
                </div>
            </div>
        </div>

        <!-- Install Instructions Modal -->
        <div id="install-instructions-modal" class="install-instructions-modal">
            <div class="modal-content">
                <div class="modal-title">How to Install XGHRM</div>
                <div id="install-steps" class="modal-steps"></div>
                <button class="modal-close" onclick="hideInstructionsModal()">Got it!</button>
            </div>
        </div>
        @env ('local')
            <script src="{{asset('js/bundle.js')}}"></script>
        @endenv
        <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('js/main-script.js')}}"></script>
        <script src="{{ asset('js/pwa-popup.js') }}"></script>
    </body>
</html>



