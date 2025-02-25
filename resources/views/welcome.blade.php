<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>E-BUILDING | KEMENKES</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{ asset('dist/img/logo-kemenkes-icon') }}" rel="icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('dist/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('dist/css/main.css') }}" rel="stylesheet">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('dist/plugins/select2/css/select2.css') }}">
</head>

<body class="index-page">

    <main class="main">

        <!-- Hero Section -->
        <section class="hero">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
                            <div class="img">
                                <img src="{{ asset('dist/img/logo-kemenkes.png') }}" style="width: 20%;">
                            </div>
                            <a href="{{ route('login') }}">
                                <h1 class="mt-4 ml-3">
                                    E-BUILDING | BIRO UMUM
                                    <p class="small">Manajemen Pengawasan Tenaga Pengelola Gedung</p>
                                </h1>
                            </a>

                            <!-- <div class="hero-buttons ml-3 mt-5">
                                <a href="{{ route('login') }}" class="btn btn-primary rounded-1 me-0 me-sm-2 mx-1">Login</a>
                            </div> -->
                        </div>
                    </div>
                </div>

            </div>

        </section><!-- /Hero Section -->

    </main>

    @if (Session::has('failed'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '{{ Session::get("failed") }}',
        });
    </script>
    @endif

    @if (Session::has('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ Session::get("success") }}',
        });
    </script>
    @endif

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Swal -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Main JS File -->
    <script src="{{ asset('dist/js/main.js') }}"></script>

</body>

</html>
