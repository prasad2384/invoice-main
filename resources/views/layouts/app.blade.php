<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <title>@yield('title')</title>
    </head>
    <body class="layout-fixed">
        <div id="wrapper">
            @include('partials.navbar')
            @yield('content')
            @include('partials.footer-scripts')
            @yield('scripts')
            @include('partials.footer')
        </div>
    </body>
</html>