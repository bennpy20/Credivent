<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" href="{{ asset('credivent.png') }}" type="image/png">

    <!-- Link ke Bootstrap CSS dari CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery dan Bootstrap JS dari CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Flatpickr CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />

    <!-- Asset lokal -->
    <link rel="stylesheet" href="{{ asset('memberast/css/open-iconic-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('memberast/css/animate.css') }}">

    <link rel="stylesheet" href="{{ asset('memberast/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('memberast/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('memberast/css/magnific-popup.css') }}">

    <link rel="stylesheet" href="{{ asset('memberast/css/aos.css') }}">

    <link rel="stylesheet" href="{{ asset('memberast/css/ionicons.min.css') }}">

    <link rel="stylesheet" href="{{ asset('memberast/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('memberast/css/jquery.timepicker.css') }}">

    <link rel="stylesheet" href="{{ asset('memberast/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('memberast/css/icomoon.css') }}">
    <link rel="stylesheet" href="{{ asset('memberast/css/style.css') }}">
</head>

<body>

    @include('components.comp_two.navbar')

    @yield('content')

    @include('components.comp_two.footer')

    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4"
                stroke="#eeeeee" />
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4"
                stroke-miterlimit="10" stroke="#F96D00" />
        </svg></div>


    <script src="{{ asset('memberast/js/jquery.min.js') }}"></script>
    <script src="{{ asset('memberast/js/jquery-migrate-3.0.1.min.js') }}"></script>
    <script src="{{ asset('memberast/js/popper.min.js') }}"></script>
    <script src="{{ asset('memberast/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('memberast/js/jquery.easing.1.3.js') }}"></script>
    <script src="{{ asset('memberast/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('memberast/js/jquery.stellar.min.js') }}"></script>
    <script src="{{ asset('memberast/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('memberast/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('memberast/js/aos.js') }}"></script>
    <script src="{{ asset('memberast/js/jquery.animateNumber.min.js') }}"></script>
    <script src="{{ asset('memberast/js/bootstrap-datepicker.js') }}"></script>
    {{-- <script src="{{ asset('memberast/js/jquery.timepicker.min.js') }}"></script> --}}
    <script src="{{ asset('memberast/js/scrollax.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
    <script src="{{ asset('memberast/js/google-map.js') }}"></script>
    <script src="{{ asset('memberast/js/main.js') }}"></script>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $(document).ready(function() {
            // Date picker: hanya tanggal, tanpa waktu
            flatpickr("#datePicker", {
                dateFormat: "Y-m-d",
                // disable time picking
                enableTime: false,
            });

            // Time picker: hanya waktu, tanpa tanggal
            flatpickr("#timePicker", {
                noCalendar: true,
                enableTime: true,
                dateFormat: "H:i",
                time_24hr: true,
            });
        });
    </script>

    {{-- <script>
        $(document).ready(function() {
            flatpickr("#datetimepicker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
            });
        });
    </script> --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const loader = document.getElementById("ftco-loader");
        if (loader) {
            loader.classList.remove("show", "fullscreen");
            loader.style.display = "none";
        }
    });
</script>

</body>

</html>
