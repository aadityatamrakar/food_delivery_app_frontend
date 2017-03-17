<!DOCTYPE html>
<html>
<head>
    <meta name="google-site-verification" content="GBAfBgjlshBVThGtK9Ju4DfimkrEzZ3s4sU6TRhmnOY" />
    <meta content="width=device-width,initial-scale=1" name=viewport>
    <title>TromBoy Food Delivery | Online Food Order</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/main.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    @yield('style')
</head>
<body>
    @include('partials.nav_main')
    @include('partials.notify')
    <div class="container" style="min-height:700px;">
        @yield('content')
    </div>
    @include('partials.footer')
    <script src="/js/jquery-3.1.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    @yield('script')
    @include('partials.tawk_chat')
</body>
</html>