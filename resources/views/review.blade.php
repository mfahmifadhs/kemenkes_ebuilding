<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">

    <style>
        body {
            background: url("{{ asset('dist/img/bg-review.jpeg') }}") no-repeat center center fixed;
            background-size: cover;
            height: 95vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }


        .title {
            color: gold;
            font-size: 32px;
            font-weight: 1000;
            text-transform: uppercase;
            letter-spacing: 15px;
            text-align: center;
            font-family: monospace;
            padding-top: 30px;
        }

        h6 {
            color: gold;
            font-size: 18px;
            font-weight: 1000;
            text-transform: uppercase;
            letter-spacing: 10px;
            text-align: center;
            font-family: monospace;
            padding-top: 30px;
        }

        .stars {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            margin: 10px 0;
            letter-spacing: 20px;
        }

        .stars input {
            display: none;
        }

        .stars label {
            font-size: 40px;
            color: white;
            cursor: pointer;
            transition: color 0.3s ease-in-out;
        }

        .stars input:checked~label,
        .stars label:hover,
        .stars label:hover~label {
            color: gold;
        }

        /* Feedback Box */
        #feedback {
            width: 100%;
            max-width: 400px;
            text-align: center;
            margin-top: 10px;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        #feedback.show {
            opacity: 1;
            transform: translateY(0);
        }

        #nohp {
            max-width: 400px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            resize: none;
        }
    </style>
</head>

<body>
    <div class="container">

        @if ($selesai)
        <h1 class="text-center text-warning mt-5">Terima Kasih !</h1>
        <h5 class="text-center text-warning">
            Penilaian anda sangat berharga, <br> untuk kami menjadi lebih baik
        </h5>
        @endif

        @if (!$selesai)
        <h6>Informasi Petugas</h6>
        <div class="row">
            <div class="col-md-4 col-4 bg-white border border-dark">
                <img src="{{ asset('dist/img/foto_pegawai/'. $data->foto_pegawai) }}" style="width: 200px; height: 150px;" class="img-fluid" alt="">
            </div>
            <div class="col-md-7 col-8 bg-white border border-dark">
                <div class="d-flex flex-wrap mt-2 text-xs">
                    <span class="w-25">NIP</span>
                    <span class="w-75">: {{ $data->nip }}</span>

                    <span class="w-25 mt-2">Nama</span>
                    <span class="w-75 mt-2">: {{ $data->nama_pegawai }}</span>

                    <span class="w-25 mt-2">Posisi</span>
                    <span class="w-75 mt-2">: {{ $data->posisi->nama_posisi }}</span>

                    <span class="w-25 mt-2">Area Kerja</span>
                    <span class="w-75 mt-2">:
                        {{ $data->area?->gedung->nama_gedung }}, {{ $data->area?->nama_area }}
                    </span>

                    <span class="w-25 mt-2">Penyedia</span>
                    <span class="w-75 mt-2">: {{ $data->penyedia->nama_penyedia }}</span>
                </div>
            </div>
        </div>
        <h3 class="title">Review</h3>

        <div class="container text-center">
            <form id="form" action="{{ route('review') }}" method="POST">
                <div class="stars">
                    <input type="radio" id="star5" name="rating" value="5">
                    <label for="star5"><i class="fas fa-star"></i></label>
                    <input type="radio" id="star4" name="rating" value="4">
                    <label for="star4"><i class="fas fa-star"></i></label>
                    <input type="radio" id="star3" name="rating" value="3">
                    <label for="star3"><i class="fas fa-star"></i></label>
                    <input type="radio" id="star2" name="rating" value="2">
                    <label for="star2"><i class="fas fa-star"></i></label>
                    <input type="radio" id="star1" name="rating" value="1">
                    <label for="star1"><i class="fas fa-star"></i></label>
                </div>

                @csrf
                <input type="hidden" name="petugas_id" value="{{ $id }}">
                <input id="nohp" type="number" class="form-control" name="no_telepon" placeholder="Nomor Handphone" required>

                <div id="feedback">
                    <textarea id="keterangan" name="keterangan" class="form-control mt-1" placeholder="Keterangan" rows="4"></textarea>
                </div>

                <button class="btn btn-default bg-warning mt-2" onclick="confirmSubmit(event)">Submit</button>
            </form>
        </div>
        @endif
    </div>

    <script>
        const stars = document.querySelectorAll('.stars input');
        const feedback = document.getElementById('feedback');

        stars.forEach(star => {
            star.addEventListener('change', function() {
                if (this.value <= 3) {
                    feedback.classList.add('show');
                } else {
                    feedback.classList.remove('show');
                }
            });
        });
    </script>

    <script>
        function confirmSubmit(event) {
            event.preventDefault();

            const form = document.getElementById('form');
            const requiredInputs = form.querySelectorAll('input[required]:not(:disabled), select[required]:not(:disabled), textarea[required]:not(:disabled)');

            let allInputsValid = true;

            requiredInputs.forEach(input => {
                if (input.value.trim() === '') {
                    input.style.borderColor = 'red';
                    allInputsValid = false;
                } else {
                    input.style.borderColor = '';
                }
            });

            if (allInputsValid) {
                Swal.fire({
                    title: 'Kirim',
                    text: '',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Proses...',
                            text: 'Mohon menunggu.',
                            icon: 'info',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        form.submit();
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Ada input yang diperlukan yang belum diisi.',
                    icon: 'error'
                });
            }
        }
    </script>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include SweetAlert JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
</body>

</html>
