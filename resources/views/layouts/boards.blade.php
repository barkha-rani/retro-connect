<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title') - RetroConnect</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('img/favicon.png') }}" rel="icon">
    <link href="{{ asset('img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


    <!-- Template Main CSS File -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/my-colors.css') }}" rel="stylesheet">

    @yield('style')
</head>

<body>

    <!-- ======= Header ======= -->
    @include('partials._header')
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    {{-- @include('partials._sidebar') --}}
    <!-- End Sidebar-->

    <main id="main" class="main" style="margin-left:0;">

        @yield('content')

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        {{-- <div class="copyright">
            &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div> --}}
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        var colorDetails = {
            "red": {
                "hex": "#F44336",
                "rgb": "rgb(244, 67, 54)",
                "bgCssClass": "bg-red",
                "txtCssClass": "text-red"
            },
            "pink": {
                "hex": "#E91E63",
                "rgb": "rgb(233, 30, 99)",
                "bgCssClass": "bg-pink",
                "txtCssClass": "text-pink"
            },
            "purple": {
                "hex": "#9C27B0",
                "rgb": "rgb(156, 39, 176)",
                "bgCssClass": "bg-purple",
                "txtCssClass": "text-purple"
            },
            "deep-purple": {
                "hex": "#673AB7",
                "rgb": "rgb(103, 58, 183)",
                "bgCssClass": "bg-deep-purple",
                "txtCssClass": "text-deep-purple"
            },
            "indigo": {
                "hex": "#3F51B5",
                "rgb": "rgb(63, 81, 181)",
                "bgCssClass": "bg-indigo",
                "txtCssClass": "text-indigo"
            },
            "blue": {
                "hex": "#2196F3",
                "rgb": "rgb(33, 150, 243)",
                "bgCssClass": "bg-blue",
                "txtCssClass": "text-blue"
            },
            "light-blue": {
                "hex": "#03A9F4",
                "rgb": "rgb(3, 169, 244)",
                "bgCssClass": "bg-light-blue",
                "txtCssClass": "text-light-blue"
            },
            "cyan": {
                "hex": "#00BCD4",
                "rgb": "rgb(0, 188, 212)",
                "bgCssClass": "bg-cyan",
                "txtCssClass": "text-cyan"
            },
            "teal": {
                "hex": "#009688",
                "rgb": "rgb(0, 150, 136)",
                "bgCssClass": "bg-teal",
                "txtCssClass": "text-teal"
            },
            "green": {
                "hex": "#4CAF50",
                "rgb": "rgb(76, 175, 80)",
                "bgCssClass": "bg-green",
                "txtCssClass": "text-green"
            },
            "light-green": {
                "hex": "#8BC34A",
                "rgb": "rgb(139, 195, 74)",
                "bgCssClass": "bg-light-green",
                "txtCssClass": "text-light-green"
            },
            "lime": {
                "hex": "#CDDC39",
                "rgb": "rgb(205, 220, 57)",
                "bgCssClass": "bg-lime",
                "txtCssClass": "text-lime"
            },
            "yellow": {
                "hex": "#FFEB3B",
                "rgb": "rgb(255, 235, 59)",
                "bgCssClass": "bg-yellow",
                "txtCssClass": "text-yellow"
            },
            "amber": {
                "hex": "#FFC107",
                "rgb": "rgb(255, 193, 7)",
                "bgCssClass": "bg-amber",
                "txtCssClass": "text-amber"
            },
            "orange": {
                "hex": "#FF9800",
                "rgb": "rgb(255, 152, 0)",
                "bgCssClass": "bg-orange",
                "txtCssClass": "text-orange"
            },
            "deep-orange": {
                "hex": "#FF5722",
                "rgb": "rgb(255, 87, 34)",
                "bgCssClass": "bg-deep-orange",
                "txtCssClass": "text-deep-orange"
            },
            "brown": {
                "hex": "#795548",
                "rgb": "rgb(121, 85, 72)",
                "bgCssClass": "bg-brown",
                "txtCssClass": "text-brown"
            },
            "grey": {
                "hex": "#9E9E9E",
                "rgb": "rgb(158, 158, 158)",
                "bgCssClass": "bg-grey",
                "txtCssClass": "text-grey"
            },
            "blue-grey": {
                "hex": "#607D8B",
                "rgb": "rgb(96, 125, 139)",
                "bgCssClass": "bg-blue-grey",
                "txtCssClass": "text-blue-grey"
            }
        }
    </script>
</body>
@yield('script')

</html>
