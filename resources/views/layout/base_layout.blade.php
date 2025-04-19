<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QaraTMS - Open Source Test Management System</title>
    <link rel="icon" type="image/x-icon" href="{{asset('/img/favicon.ico')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="{{asset('css/main.css')}}" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
    <script src="{{asset('js/js.cookie.min.js')}}"></script>
    @yield('head')
</head>
<body>
<div class="row sticky-top">
    @include('layout.header_nav')
</div>
<div class="container-fluid">
    <div class="row fh">
        @yield('content')
    </div>
</div>

<div class="modal fade" id="any_img_lightbox" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="position-absolute top-50 start-50 translate-middle">
            <img id="any_img_lightbox_image" src="" alt="">
        </div>
    </div>
</div>

<div class="modal fade" id="test_case_overlay" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl" id="test_case_overlay_data">
    </div>
</div>

<script src="{{asset('js/main.js')}}"></script>
@yield('footer')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
