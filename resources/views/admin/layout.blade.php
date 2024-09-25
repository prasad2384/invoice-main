<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('admin.partials.head')
        <title>@yield('title')</title>
    </head>
    <body class="layout-fixed">
        <div id="wrapper">
            @include('admin.partials.navbar')
            @include('admin.partials.sidebar')
            @yield('content')
            @include('admin.partials.footer-scripts')
            @yield('scripts')
            @include('admin.partials.footer')
        </div>
    </body>
</html>
